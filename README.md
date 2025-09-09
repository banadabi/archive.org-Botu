# Archive.org Importer (archive.org-Botu)

Import items from the Internet Archive (archive.org) into your WordPress site with ease. This plugin helps you search for Archive.org items, preview their metadata, and import selected content as WordPress posts and media.

> Note: This project is not affiliated with or endorsed by the Internet Archive.

## Features

- Search Archive.org collections and items by identifier or query
- Preview item metadata before importing
- Import titles, descriptions, creators, dates, and subjects/tags
- Optionally download and attach files (e.g., audio, video, PDFs) as WordPress media
- Create posts for imported items and assign categories/tags
- Basic rate‑limit friendly behavior to avoid excessive requests
- Admin UI for configuration and one‑click imports

## Requirements

- PHP 7.4 or newer (PHP 8.x recommended)
- WordPress 5.8+ (WordPress 6.x recommended)
- Curl or allow_url_fopen enabled for remote requests (one is sufficient)

## Installation

1. Download or clone this repository.
2. Place the folder in your WordPress installation under:
   - `wp-content/plugins/archive-org-importer/`
3. Ensure the main plugin file `archive-org-importer.php` is directly inside that folder.
4. In your WordPress Dashboard, go to Plugins and activate “Archive.org Importer”.

## Configuration

After activation:

- Go to: Dashboard → Settings → Archive.org Importer (or the plugin’s dedicated menu under “Archive.org” in the sidebar).
- Review and adjust:
  - Default import post type (e.g., Posts)
  - Whether to download files (attach to media library) or link externally
  - Category/tag mapping behavior
  - Any timeouts or throttling settings to respect rate limits

Archive.org generally does not require an API key for its metadata endpoints. If you use features that rely on special endpoints, follow on‑screen notes in the settings page.

## Usage

1. Open the plugin’s admin page.
2. Choose your import method:
   - Search: enter keywords, collection names, or filters supported by Archive.org
   - Direct import: paste a specific Archive.org identifier (e.g., `castle_of_otranto_1005_librivox`)
3. Preview the results and metadata.
4. Select items to import and confirm:
   - The plugin will create posts and, if enabled, download selected files into your Media Library.
5. Review imported content in Posts → All Posts or in your chosen post type.

## Notes on Archive.org and Rate Limits

- Be mindful of Archive.org’s infrastructure. Avoid large bursts of requests.
- If you see timeouts or partial imports, increase the timeout in settings and reduce batch size.
- Consider importing during off‑peak hours for smoother performance.

## Project Structure

- `archive-org-importer.php` — Main plugin bootstrap and entry point
- `admin/` — Admin pages, settings, and UI logic
- `includes/` — Core functionality (API calls, import helpers, utilities)

## Troubleshooting

- Metadata not loading:
  - Check your server can make outbound HTTP requests (cURL or allow_url_fopen).
  - Verify no firewall/hosting restrictions block archive.org.
- Files not downloading:
  - Confirm media upload permissions and available disk space.
  - Try switching between external linking and file downloading in settings.
- Slow imports:
  - Lower batch size, increase timeouts, or import fewer items at a time.

## Contributing

Contributions are welcome:
- Fork the repository
- Create a feature branch
- Open a pull request with a clear description and minimal, focused changes

Please include steps to reproduce for bug fixes and basic test coverage where applicable.

## Security

If you discover a security vulnerability, please do not open a public issue. Instead, contact the repository owner privately and allow time for a fix.

## License

This repository currently does not specify a license. Unless a license is added, all rights are reserved by the repository owner.





# Archive.org İçerik Aktarma Botu

Bu WordPress eklentisi, Archive.org üzerindeki içerikleri WordPress sitenize kolayca aktarmanızı sağlar.

## Özellikler

- Archive.org URL'sinden içerik çekme
- Dosya türlerine göre filtreleme
- Toplu içerik aktarma
- Kategori seçme
- Dosya boyutu ve türü görüntüleme

## Kurulum

1. Eklenti dosyalarını `/wp-content/plugins/archive-org-importer` dizinine yükleyin
2. WordPress yönetici panelinden eklentiyi etkinleştirin
3. Sol menüde "Archive İçeri Aktar" seçeneğini göreceksiniz

## Kullanım

1. Archive.org'dan almak istediğiniz içeriğin URL'sini girin
2. "İçeriği Çek" butonuna tıklayın
3. Gelen listeden istediğiniz dosyaları seçin
4. Her dosya için kategori belirleyin
5. "Seçilenleri İçe Aktar" butonuyla içerikleri sitenize ekleyin

## Gereksinimler

- WordPress 5.0 veya üzeri
- PHP 7.0 veya üzeri 
