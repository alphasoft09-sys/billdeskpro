# CDN Links Documentation

This document contains all the CDN links used in the BillDesk Pro application. All files have been downloaded and stored locally for offline use.

## JavaScript Libraries

### jQuery
- **CDN Link**: `https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js`
- **Local Path**: `public/js/vendor/jquery.min.js`
- **Version**: 3.4.1
- **Purpose**: JavaScript library for DOM manipulation and AJAX requests

### Selectize.js
- **CDN Link**: `https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js`
- **Local Path**: `public/js/vendor/selectize.min.js`
- **Version**: 0.12.6
- **Purpose**: Advanced select element with search, tagging, and remote data loading capabilities

## CSS Libraries

### Selectize Bootstrap3 Theme
- **CDN Link**: `https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css`
- **Local Path**: `public/css/vendor/selectize.bootstrap3.min.css`
- **Version**: 0.12.6
- **Purpose**: Bootstrap 3 compatible styling for Selectize.js components

## Implementation

All select elements in the application are enhanced with Selectize.js for better user experience:

```javascript
$(document).ready(function() {
  $('select').selectize({
    sortField: 'text'
  });
});
```

## File Structure

```
public/
├── js/
│   └── vendor/
│       ├── jquery.min.js
│       └── selectize.min.js
└── css/
    └── vendor/
        └── selectize.bootstrap3.min.css
```

## Usage in Templates

Include these files in your Blade templates:

```html
<!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/vendor/selectize.bootstrap3.min.css') }}">

<!-- JavaScript -->
<script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
<script src="{{ asset('js/vendor/selectize.min.js') }}"></script>
```

## Benefits

1. **Offline Access**: All libraries are stored locally, ensuring the application works without internet connection
2. **Performance**: Faster loading times as files are served from the same domain
3. **Reliability**: No dependency on external CDN availability
4. **Version Control**: Exact versions are maintained and tracked
5. **Enhanced UX**: Selectize provides search, tagging, and better select element functionality

## Last Updated

- **Date**: September 18, 2025
- **Downloaded Files**: jQuery 3.4.1, Selectize.js 0.12.6, Selectize Bootstrap3 CSS 0.12.6
