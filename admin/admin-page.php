<?php
if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h1>Archive.org İçeri Aktarma</h1>
    
    <div class="card">
        <h2>URL'den İçerik Çek</h2>
        <p>
            <input type="text" id="archive_url" class="regular-text" placeholder="https://archive.org/details/...">
            <button class="button button-primary" id="fetch_content">İçeriği Çek</button>
        </p>
    </div>
    
    <div id="content_list" style="display:none;">
        <div class="extension-filters" style="margin: 20px 0;">
            <h3 style="display: inline-block; margin-right: 15px;">Dosya Türleri:</h3>
            <div id="extension-buttons" class="button-group">
                <!-- JavaScript ile doldurulacak -->
            </div>
        </div>

        <form id="import_form">
            <div class="tablenav top">
                <div class="alignleft actions">
                    <select name="bulk_category" id="bulk_category">
                        <option value="">Kategori Seç</option>
                        <?php
                        $categories = get_categories(['hide_empty' => false]);
                        foreach ($categories as $category) {
                            echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                        }
                        ?>
                    </select>
                    <button type="button" class="button" id="select_all">Tümünü Seç</button>
                    <button type="submit" class="button button-primary">Seçilenleri İçe Aktar</button>
                </div>
            </div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="check-column"><input type="checkbox" id="cb-select-all"></th>
                        <th>Başlık</th>
                        <th>Link</th>
                        <th>Boyut</th>
                        <th>Tür</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody id="content_items">
                    <!-- JavaScript ile doldurulacak -->
                </tbody>
            </table>
        </form>
    </div>
</div>

<style>
.extension-button {
    display: inline-block;
    padding: 5px 10px;
    margin: 0 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    cursor: pointer;
}
.extension-button.active {
    background-color: #2271b1;
    color: white;
    border-color: #2271b1;
}
.extension-count {
    background: #e5e5e5;
    border-radius: 10px;
    padding: 2px 8px;
    margin-left: 5px;
    font-size: 0.9em;
}
.active .extension-count {
    background: rgba(255,255,255,0.3);
}
</style>

<script>
jQuery(document).ready(function($) {
    let allItems = []; // Tüm dosyaları saklamak için
    let currentItems = []; // Şu an görüntülenen (filtrelenmiş) dosyalar için
    
    $('#fetch_content').click(function() {
        var url = $('#archive_url').val();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'fetch_archive_content',
                url: url
            },
            success: function(response) {
                if (response.success) {
                    allItems = response.data;
                    updateExtensionFilters();
                    displayItems(allItems);
                }
            }
        });
    });
    
    function updateExtensionFilters() {
        // Uzantıları say
        let extensions = {};
        allItems.forEach(item => {
            if (!extensions[item.extension]) {
                extensions[item.extension] = 0;
            }
            extensions[item.extension]++;
        });
        
        // Filtre butonlarını oluştur
        let html = `<div class="extension-button active" data-ext="all">
            Tümü <span class="extension-count">${allItems.length}</span>
        </div>`;
        
        Object.keys(extensions).sort().forEach(ext => {
            html += `<div class="extension-button" data-ext="${ext}">
                ${ext.toUpperCase()} <span class="extension-count">${extensions[ext]}</span>
            </div>`;
        });
        
        $('#extension-buttons').html(html);
    }
    
    // Filtre butonlarına tıklama
    $(document).on('click', '.extension-button', function() {
        $('.extension-button').removeClass('active');
        $(this).addClass('active');
        
        let ext = $(this).data('ext');
        let filteredItems = ext === 'all' ? allItems : allItems.filter(item => item.extension === ext);
        displayItems(filteredItems);
    });
    
    function displayItems(items) {
        currentItems = items; // Görüntülenen öğeleri sakla
        var html = '';
        items.forEach(function(item, index) {
            // Gerçek array indeksini sakla
            let realIndex = allItems.indexOf(item);
            html += `
                <tr>
                    <td><input type="checkbox" name="items[]" value="${realIndex}"></td>
                    <td>${item.title}</td>
                    <td><a href="${item.link}" target="_blank">${item.link}</a></td>
                    <td>${item.size}</td>
                    <td>${item.extension.toUpperCase()}</td>
                    <td>
                        <select name="category_${realIndex}">
                            <option value="">Kategori Seç</option>
                            <?php
                            foreach ($categories as $category) {
                                echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            `;
        });
        
        $('#content_items').html(html);
        $('#content_list').show();
    }
    
    // Üst kategori seçimi değiştiğinde
    $('#bulk_category').change(function() {
        var selectedCategory = $(this).val();
        if (selectedCategory) {
            // Görüntülenen tüm öğelerin kategori seçimini güncelle
            $('select[name^="category_"]').val(selectedCategory);
        }
    });
    
    // Tümünü Seç butonu için olay dinleyici
    $('#select_all').click(function() {
        // Önce tüm görünen öğeleri seç
        $('input[name="items[]"]').prop('checked', true);
        
        // Üst kategoriden seçili bir kategori varsa onu uygula
        var selectedCategory = $('#bulk_category').val();
        if (selectedCategory) {
            $('select[name^="category_"]').val(selectedCategory);
        }
    });
    
    // Üst kısımdaki checkbox için olay dinleyici
    $('#cb-select-all').change(function() {
        // Önce tüm görünen öğeleri seç/seçimi kaldır
        $('input[name="items[]"]').prop('checked', this.checked);
        
        // Üst kategoriden seçili bir kategori varsa onu uygula
        if (this.checked) {
            var selectedCategory = $('#bulk_category').val();
            if (selectedCategory) {
                $('select[name^="category_"]').val(selectedCategory);
            }
        }
    });
    
    $('#import_form').submit(function(e) {
        e.preventDefault();
        
        var selectedItems = [];
        $('input[name="items[]"]:checked').each(function() {
            var index = $(this).val();
            var item = allItems[index];
            var category = $(`select[name="category_${index}"]`).val();
            
            // Kategori seçilmemişse işleme alma
            if (!category) {
                return;
            }
            
            selectedItems.push({
                title: item.title,
                link: item.link,
                content: item.link,
                category: category
            });
        });
        
        if (selectedItems.length === 0) {
            alert('Lütfen kategori seçin ve en az bir öğe seçin!');
            return;
        }
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'import_archive_items',
                items: selectedItems
            },
            success: function(response) {
                if (response.success) {
                    alert('Seçilen öğeler başarıyla içe aktarıldı!');
                }
            }
        });
    });
});
</script> 