// Filter Cascade untuk Lokasi -> Kandang -> Bibit
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Tom Select untuk semua select
    const tomSelects = {};
    
    // Function untuk update kandang berdasarkan lokasi
    function updateKandangByLokasi(lokasiId, kandangSelectId, availableOnly = false, callback = null) {
        const kandangSelect = document.getElementById(kandangSelectId);
        if (!kandangSelect || !tomSelects[kandangSelectId]) {
            if (callback) callback();
            return;
        }
        
        const kandangTomSelect = tomSelects[kandangSelectId];
        
        // Clear existing options
        kandangTomSelect.clearOptions();
        kandangTomSelect.clear();
        
        if (!lokasiId) {
            // Load all kandangs from existing options (filter yang belum punya bibit jika availableOnly)
            const options = kandangSelect.querySelectorAll('option');
            options.forEach(option => {
                if (option.value) {
                    const optionText = option.textContent;
                    const hasBibit = optionText.includes('Sudah Ada Bibit');
                    
                    if (!availableOnly || !hasBibit) {
                        kandangTomSelect.addOption({
                            value: option.value,
                            text: optionText.replace(' (Sudah Ada Bibit)', '')
                        });
                    }
                }
            });
            kandangTomSelect.refreshOptions(false);
            if (callback) callback();
            return;
        }
        
        // Fetch kandangs by lokasi
        const url = availableOnly 
            ? `/api/kandang?lokasi_id=${lokasiId}&available_only=1`
            : `/api/kandang?lokasi_id=${lokasiId}`;
            
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                data.forEach(kandang => {
                    const text = kandang.bibit 
                        ? `${kandang.nama_kandang} (Sudah Ada Bibit)` 
                        : kandang.nama_kandang;
                    
                    // Store lokasi_id in data attribute for reverse lookup
                    kandangTomSelect.addOption({
                        value: kandang.id,
                        text: text,
                        lokasi_id: kandang.lokasi_id
                    });
                });
                kandangTomSelect.refreshOptions(false);
                
                // Execute callback setelah option ter-update
                if (callback) callback();
            })
            .catch(error => {
                console.error('Error fetching kandangs:', error);
                if (callback) callback();
            });
    }
    
    // Function untuk update bibit berdasarkan kandang
    function updateBibitByKandang(kandangId, bibitSelectId) {
        // Skip jika sedang auto-filling dari bibit (mencegah infinite loop)
        if (isAutoFilling) {
            return;
        }
        
        const bibitSelect = document.getElementById(bibitSelectId);
        if (!bibitSelect || !tomSelects[bibitSelectId]) return;
        const isFilter = bibitSelectId === 'filter_bibit';
        const previousValue = tomSelects[bibitSelectId].getValue();

        // NEW: Auto-select Lokasi if Kandang is selected (Reverse Cascade)
        // Find the kandang select element that triggered this
        const kandangSelect = document.querySelector(`select[id="${bibitSelectId.replace('bibit_id', 'kandang_id').replace('filter_bibit', 'filter_kandang')}"]`) || 
                             document.querySelector(`select[data-target-bibit="${bibitSelectId}"]`);
                             
        if (kandangId && kandangSelect) {
             const kandangTomSelect = tomSelects[kandangSelect.id];
             // Try to find the option object to get stored lokasi_id
             const option = kandangTomSelect.options[kandangId];
             
             // Determine target lokasi select ID
             const lokasiSelectId = kandangSelect.getAttribute('data-target-lokasi') || 
                                   (kandangSelect.id === 'filter_kandang' ? 'filter_lokasi' : 'lokasi_id');
             const lokasiTomSelect = tomSelects[lokasiSelectId];

             if (lokasiTomSelect) {
                 let lokasiId = null;
                 
                 // Get lokasi_id from JS option data
                 if (option && option.lokasi_id) {
                     lokasiId = option.lokasi_id;
                 } else {
                     // Fallback: get from original HTML option data-lokasi attribute
                     const htmlOption = kandangSelect.querySelector(`option[value="${kandangId}"]`);
                     if (!htmlOption) {
                         // Try native select
                         const nativeSelect = document.querySelector(`select#${kandangSelect.id}`);
                         if (nativeSelect) {
                             const nativeOption = nativeSelect.querySelector(`option[value="${kandangId}"]`);
                             if (nativeOption) {
                                 lokasiId = nativeOption.getAttribute('data-lokasi');
                             }
                         }
                     } else {
                         lokasiId = htmlOption.getAttribute('data-lokasi');
                     }
                 }
                 
                 if (lokasiId) {
                     isAutoFilling = true;
                     if (lokasiTomSelect.getValue() != lokasiId) {
                         lokasiTomSelect.setValue(lokasiId);
                     }
                     setTimeout(() => { isAutoFilling = false; }, 150);
                 }
             }
        }
        
        tomSelects[bibitSelectId].clearOptions();
        tomSelects[bibitSelectId].clear();

        if (isFilter) {
            tomSelects[bibitSelectId].addOption({ value: '', text: 'Semua Bibit' });
        }
        
        if (!kandangId) {
            // If no kandang selected, show all bibits (for filter form)
            if (bibitSelectId === 'filter_bibit') {
                const options = bibitSelect.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value) {
                        tomSelects[bibitSelectId].addOption({
                            value: option.value,
                            text: option.textContent
                        });
                    }
                });
                tomSelects[bibitSelectId].refreshOptions(false);
            }
            return;
        }
        
        fetch(`/api/bibit?kandang_id=${kandangId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.length > 0) {
                    data.forEach(bibit => {
                        let text = `${bibit.jenis_bibit} - ${bibit.kandang?.nama_kandang || ''}`;
                        if (bibit.status !== 'aktif') {
                            text += ' [Selesai]';
                        }
                        tomSelects[bibitSelectId].addOption({
                            value: bibit.id,
                            text: text
                        });
                    });
                    tomSelects[bibitSelectId].refreshOptions(false);

                    if (isFilter && previousValue) {
                        tomSelects[bibitSelectId].setValue(previousValue, true);
                    }
                    // Auto-select jika hanya ada 1 (only for form, not filter)
                    // Tapi skip jika sedang auto-filling untuk mencegah loop
                    if (bibitSelectId !== 'filter_bibit' && !isAutoFilling && data.length === 1) {
                        tomSelects[bibitSelectId].setValue(data[0].id);
                    }
                } else {
                    // No bibit found — keep only "Semua Bibit" option for filter
                    if (bibitSelectId === 'filter_bibit') {
                        tomSelects[bibitSelectId].refreshOptions(false);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching bibit:', error);
            });
    }
    
    // Flag untuk mencegah infinite loop
    let isAutoFilling = false;
    
    // Auto-fill lokasi dan kandang dari bibit
    function autoFillFromBibit(bibitId, lokasiSelectId, kandangSelectId) {
        if (!bibitId || isAutoFilling) return;
        
        isAutoFilling = true;
        
        const lokasiTomSelect = tomSelects[lokasiSelectId];
        const kandangTomSelect = tomSelects[kandangSelectId];
        
        if (!lokasiTomSelect || !kandangTomSelect) {
            console.error('TomSelect not found for lokasi or kandang');
            isAutoFilling = false;
            return;
        }
        
        fetch(`/absensi/autofill/${bibitId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.lokasi_id) {
                    // 1. Set lokasi (tanpa trigger event)
                    lokasiTomSelect.setValue(data.lokasi_id, true);
                    
                    // 2. Update kandang by lokasi dengan callback
                    updateKandangByLokasi(data.lokasi_id, kandangSelectId, false, () => {
                        // 3. Set kandang setelah option ter-update (tanpa trigger event)
                        if (data.kandang_id) {
                            kandangTomSelect.setValue(data.kandang_id, true);
                        }
                        // Reset flag setelah selesai
                        setTimeout(() => {
                            isAutoFilling = false;
                        }, 100);
                    });
                } else {
                    isAutoFilling = false;
                }
            })
            .catch(error => {
                console.error('Error fetching bibit:', error);
                isAutoFilling = false;
            });
    }

    // Initialize Tom Select untuk select dengan class 'tom-select'
    document.querySelectorAll('select.tom-select').forEach(select => {
        if (!select.id) {
            select.id = 'tom-select-' + Math.random().toString(36).substr(2, 9);
        }
        
        const options = {
            placeholder: select.getAttribute('placeholder') || 'Pilih...',
            allowEmptyOption: true,
        };
        
        try {
            tomSelects[select.id] = new TomSelect(`#${select.id}`, options);
        } catch (e) {
            console.error('Error initializing TomSelect:', e);
        }
    });
    
    // Setup cascade untuk lokasi -> kandang
    document.querySelectorAll('#lokasi_id, #filter_lokasi').forEach(lokasiSelect => {
        if (lokasiSelect.id && tomSelects[lokasiSelect.id]) {
            const kandangSelectId = lokasiSelect.getAttribute('data-target-kandang') || 'kandang_id';
            const formEl = lokasiSelect.closest('form');
            const method = (formEl?.getAttribute('method') || 'GET').toUpperCase();
            const isBibitForm = lokasiSelect.id === 'lokasi_id' && method !== 'GET' && formEl && formEl.action.includes('bibit');
            
            tomSelects[lokasiSelect.id].on('change', function(value) {
                if (isAutoFilling) {
                    return;
                }

                updateKandangByLokasi(value, kandangSelectId, isBibitForm);
                
                // If this is filter form, also update bibit filter
                if (lokasiSelect.id === 'filter_lokasi') {
                    const bibitSelectId = 'filter_bibit';
                    if (tomSelects[bibitSelectId]) {
                        // Clear bibit filter when lokasi changes
                        tomSelects[bibitSelectId].clear();
                    }
                }
            });
        }
    });
    
    // Setup cascade untuk kandang -> bibit
    document.querySelectorAll('#kandang_id, #filter_kandang').forEach(kandangSelect => {
        if (kandangSelect.id && tomSelects[kandangSelect.id]) {
            const bibitSelectId = kandangSelect.getAttribute('data-target-bibit') || 
                                 (kandangSelect.id === 'filter_kandang' ? 'filter_bibit' : 'bibit_id');
            
            tomSelects[kandangSelect.id].on('change', function(value) {
                // Skip jika sedang auto-filling dari bibit (mencegah infinite loop)
                if (isAutoFilling) {
                    return;
                }
                updateBibitByKandang(value, bibitSelectId);
            });
        }
    });
    
    // Setup auto-fill dari bibit
    document.querySelectorAll('#bibit_id, #filter_bibit').forEach(bibitSelect => {
        if (bibitSelect.id && tomSelects[bibitSelect.id]) {
            const isFilter = bibitSelect.id === 'filter_bibit';
            const lokasiSelectId = bibitSelect.getAttribute('data-target-lokasi') || (isFilter ? 'filter_lokasi' : 'lokasi_id');
            const kandangSelectId = bibitSelect.getAttribute('data-target-kandang') || (isFilter ? 'filter_kandang' : 'kandang_id');
            
            tomSelects[bibitSelect.id].on('change', function(value) {
                if (isFilter && !value) {
                    updateBibitByKandang(null, 'filter_bibit');
                    return;
                }

                // Skip jika value sama dengan yang sudah dipilih (mencegah loop)
                const currentValue = tomSelects[bibitSelect.id].getValue();
                if (value && value === currentValue && isAutoFilling) {
                    return;
                }
                autoFillFromBibit(value, lokasiSelectId, kandangSelectId);
            });

            const initialValue = tomSelects[bibitSelect.id].getValue();
            if (initialValue) {
                autoFillFromBibit(initialValue, lokasiSelectId, kandangSelectId);
            }
        }
    });
    
    // Export untuk global access
    window.tomSelects = tomSelects;
    window.updateKandangByLokasi = updateKandangByLokasi;
    window.updateBibitByKandang = updateBibitByKandang;
    window.autoFillFromBibit = autoFillFromBibit;
});
