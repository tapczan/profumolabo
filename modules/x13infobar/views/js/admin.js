$(document).ready(function() {
    setTimeout(function() {
      handleX13InfoBarType();
      handleX13Button();
    }, 500);
    
    $('#type').on('change', handleX13InfoBarType);
    // $('input[name="button"]').on('change', handleX13Button);

    $('#date_from, #date_to').attr('autocomplete', 'off');

  if (previewInfobar) {
    var openBlank = window.open('about:blank', '_blank', '');
    if (openBlank !== null) {
      openBlank.document.write(
        '<p style="text-align: center;">' +
        '<img src="' + document.location.origin + ad + '/themes/default/img/select2-spinner.gif">' +
        '</p>'
      );
      setTimeout(() => {
        openBlank.location = previewInfobarUrl;
      }, 1000);
    }
  }
 });

var x13InfoBarType;
var x13InfoBarCounterTypeWrapper = '.type_counter';
var x13InfoBarButtonSettingsWrapper = '.button_enabled';

var x13InfoBar_tagButton = '[button]';
var x13InfoBar_tagCounter = '[counter]';

function handleX13InfoBarType()
{
  var choice = $('#type').val();
  if (choice == 1) {
    $(x13InfoBarCounterTypeWrapper).hide();
  } else {
    $(x13InfoBarCounterTypeWrapper).show();
  }
}

function handleX13Button()
{
  var choice = $('input[name="button"]:checked').val();
  if (choice == 1) {
    $(x13InfoBarButtonSettingsWrapper).show();
  } else {
    $(x13InfoBarButtonSettingsWrapper).hide();
  }
}

function x13InsertSection(e)
{
  e.preventDefault();
  if (typeof tinyMCE.activeEditor !== 'undefined') {
    var editor = tinyMCE.activeEditor;
    editor.execCommand('mceInsertContent', false, '&nbsp;<hr>\nText text text');
  } else {
    $('iframe[id*="text_"]:visible').each(function(index, el) {
      var text = $(el).contents().find('#tinymce');
      if (text.length) {
        text.html(text.html() + '&nbsp;\n<hr>\nText text text');
      }
    });
  }

  if ($('[name="styling[min_height]"]').val() == '' || ($('[name="styling[min_height]"]').val() != '' && parseInt($('[name="styling[min_height]"]').val()) == 0)) {
    $('[name="styling[min_height]"]').val('56px');
  }
}

function x13InsertCounter(e)
{
  $('#type').val(2);
  handleX13InfoBarType();

  if (typeof tinyMCE.activeEditor !== 'undefined') {
    var editor = tinyMCE.activeEditor;
    editor.execCommand('mceInsertContent', false, '&nbsp;[counter]');
  } else {
    $('iframe[id*="text_"]:visible').each(function(index, el) {
      var text = $(el).contents().find('#tinymce');
        text.html(text.html() + '&nbsp;[counter]');
    });
  }

  e.preventDefault();
}

function x13InsertButton(e)
{
  $('#button_on').trigger('click');

  handleX13Button();

  if (typeof tinyMCE.activeEditor !== 'undefined') {
    var editor = tinyMCE.activeEditor;
    editor.execCommand('mceInsertContent', false, '&nbsp;[button]');
  } else {
    $('iframe[id*="text_"]:visible').each(function(index, el) {
      var text = $(el).contents().find('#tinymce');
        text.html(text.html() + '&nbsp;[button]');
    });
  }

  e.preventDefault();
}