<?php
class ArchiveScraper {
    private $url;
    private $allowed_extensions;
    
    public function __construct($url, $allowed_extensions = ['mp3']) {
        $this->url = $url;
        $this->allowed_extensions = $allowed_extensions;
    }
    
    private function get_file_extension($filename) {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
    
    public function fetch_content() {
        // cURL ile içeriği çek
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL sertifika doğrulamasını devre dışı bırak
        $html = curl_exec($ch);
        curl_close($ch);
        
        // DOMDocument ile parse et
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // HTML hatalarını görmezden gel
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();
        
        $xpath = new DOMXPath($dom);
        
        $items = [];
        
        // Dosya listesini çek
        $files = $xpath->query('//div[@class="format-file"]');
        
        if ($files && $files->length > 0) {
            foreach ($files as $file) {
                $link = $xpath->query('.//a[@class="stealth download-pill"]', $file);
                if ($link->length > 0) {
                    $href = $link->item(0)->getAttribute('href');
                    $title = trim($link->item(0)->textContent);
                    
                    // Dosya uzantısını al
                    $extension = $this->get_file_extension($href);
                    
                    // Dosya boyutunu al
                    $size = $xpath->query('.//div[@class="down-rite"]', $file);
                    $filesize = $size->length > 0 ? trim($size->item(0)->textContent) : '';
                    
                    $items[] = [
                        'title' => $title,
                        'link' => 'https://archive.org' . $href,
                        'size' => $filesize,
                        'extension' => $extension,
                        'category' => 'vaaz'
                    ];
                }
            }
        }
        
        return $items;
    }
} 