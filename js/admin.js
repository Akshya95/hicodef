/**
 * HICODEF Admin — admin.js
 * Handles the WordPress media library picker for image/logo selection.
 * All saves are standard PHP form POSTs — no AJAX needed.
 */
(function ($) {
  'use strict';

  $(document).ready(function () {

    // ── Media library picker ───────────────────────────────
    $(document).on('click', '.hm-media-btn', function (e) {
      e.preventDefault();
      var $btn         = $(this);
      var targetId     = $btn.data('target');
      var previewId    = $btn.data('preview');
      var placeholderId= $btn.data('placeholder');

      var frame = wp.media({
        title:    'Select image',
        button:   { text: 'Use this image' },
        multiple: false,
        library:  { type: 'image' },
      });

      frame.on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();
        // Prefer medium size, fall back to full
        var url = (attachment.sizes && attachment.sizes.medium)
          ? attachment.sizes.medium.url
          : attachment.url;

        // Set hidden URL input
        $('#' + targetId).val(url);

        // Update preview image if present
        if (previewId) {
          var $prev = $('#' + previewId);
          $prev.attr('src', url).show();
        }

        // Hide placeholder text if present
        if (placeholderId) {
          $('#' + placeholderId).hide();
        }

        // Update preview inside edit rows (for existing slides)
        var $row = $btn.closest('.hm-edit-fields');
        if ($row.length) {
          var $img = $row.find('.hm-edit-preview');
          if ($img.length) {
            $img.attr('src', url);
          } else {
            $row.find('div:first').prepend('<img src="' + url + '" class="hm-edit-preview">');
          }
        }
      });

      frame.open();
    });

    // ── Edit row toggle ────────────────────────────────────
    $(document).on('click', '.hm-toggle-edit', function () {
      var id  = $(this).data('id');
      var $row = $('#edit-' + id);
      $row.slideToggle(200);
      $(this).text($row.is(':hidden') ? 'Edit' : 'Cancel');
    });

  });

})(jQuery);
