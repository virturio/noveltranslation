# Novel Translation WordPress Theme

A modern and simple WordPress theme specifically designed for novel translation websites. This theme provides a clean and user-friendly interface for displaying translated chapters and series.

## Features

- Custom post types for Novels(Series) and Chapters
- Custom taxonomy for Novels(Series) genre
- Include custom plugin for managing chapters and Novels(Series)
- Custom url for better SEO
- Build-in breadcrumb
- Responsive design for all devices
- Modern and clean UI
- Optimized for reading long-form content
- Custom archive pages for better content organization
- Build-in Ko-fi button for receiving donation
- Custom searchbar for searching Novels(Series) or chapter
- Optimized for cached environment like CDN
- Custom comments forms

## Requirements

- WordPress 5.4 or higher
- PHP 8.0 or higher
- Modern web browser with JavaScript enabled

## Installation

1. Download/clone this repository
2. Upload the [mu-plugin](/mu-plugins) to the `/wp-content/mu-plugins` directory (create new folder if it does'nt exists )
3. Upload the theme folder to the `/wp-content/themes/` directory
4. Activate the theme through the WordPress admin panel (Appearance > Themes)
5. Configure theme options as needed

## Theme Structure

```
noveltranslation/
├── assets/           # Theme assets (CSS, JS, images)
├── inc/             # Include files and functions
├── functions.php    # Theme functions
├── header.php       # Header template
├── footer.php       # Footer template
├── index.php        # Main template file
├── page.php         # Page template
├── single-series.php    # Single series template
├── single-chapter.php   # Single chapter template
├── archive-series.php   # Series archive template
├── front-page.php       # Front page template
└── style.css        # Theme stylesheet and information
```

## Custom Post Types

The theme includes two custom post types:

1. **Series**: For organizing related novels
2. **Chapters**: For individual chapter content

## Custom Taxonomies

The theme includes one custom taxonomies:

1. **Genre**: For related novels

### Prerequisites

- Node.js and npm installed
- WordPress development environment

### Setup

1. Clone the repository
2. Install dependencies:
   ```bash
   npm install
   ```
3. Make your changes
4. Test thoroughly before deployment

## Support

For support, please create an issue in the repository or contact the theme author.

## License

This theme is licensed under the GNU General Public License v2 or later.

## Credits

- Author: Muhammad Rio
- Version: 1.1
- Tested up to WordPress 6.7.2

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Changelog

### 1.1

- Initial release with basic functionality
- Custom post types implementation
- Basic theme structure and templates

---

Feel free to modify and enhance it according to your needs. Please hit the **⭐Star** button if you find this repository helpful.
