<?php

add_action('customize_register', 'dooplay_child_customizer');
function dooplay_child_customizer($customizer) {
if (class_exists('WP_Customize_Control')) {
  class WP_Customize_Teeny_Control extends WP_Customize_Control {
    function __construct($manager, $id, $options) {
      parent::__construct($manager, $id, $options);

      global $num_customizer_teenies_initiated;
      $num_customizer_teenies_initiated = empty($num_customizer_teenies_initiated)
        ? 1
        : $num_customizer_teenies_initiated + 1;
    }
    function render_content() {
      global $num_customizer_teenies_initiated, $num_customizer_teenies_rendered;
      $num_customizer_teenies_rendered = empty($num_customizer_teenies_rendered)
        ? 1
        : $num_customizer_teenies_rendered + 1;

      $value = $this->value();
      ?>
        <label>
          <span class="customize-text_editor"><?php echo esc_html($this->label); ?></span>
          <input id="<?php echo $this->id ?>-link" class="wp-editor-area" type="hidden" <?php $this->link(); ?> value="<?php echo esc_textarea($value); ?>">
          <?php
            wp_editor($value, $this->id, [
              'textarea_name' => $this->id,
              'media_buttons' => false,
              'drag_drop_upload' => false,
              'teeny' => true,
              'quicktags' => false,
              'textarea_rows' => 5,
              // MAKE SURE TINYMCE CHANGES ARE LINKED TO CUSTOMIZER
              'tinymce' => [
                'setup' => "function (editor) {
                  var cb = function () {
                    var linkInput = document.getElementById('$this->id-link')
                    linkInput.value = editor.getContent()
                    linkInput.dispatchEvent(new Event('change'))
                  }
                  editor.on('Change', cb)
                  editor.on('Undo', cb)
                  editor.on('Redo', cb)
                  editor.on('KeyUp', cb) // Remove this if it seems like an overkill
                }"
              ]
            ]);
          ?>
        </label>
      <?php
      // PRINT THEM ADMIN SCRIPTS AFTER LAST EDITOR
      if ($num_customizer_teenies_rendered == $num_customizer_teenies_initiated)
        do_action('admin_print_footer_scripts');
    }
  }
}

	$customizer->add_section('dooplay_child', [
		'title' => 'Dooplay theme',
		'priority' => 100,
	]);
	$customizer->add_setting('background', [
		'default' => '',
		'transport' => 'refresh',
	]);
	$customizer->add_control(new WP_Customize_Image_Control(
		$customizer,
		'background',
		[
			'label' => __('Select background'),
			'section' => 'dooplay_child',
		]
	));

	$customizer->add_section('dooplay_child_home', [
		'title' => __('Home page'),
		'transport' => 'refresh',
	]);
	$customizer->add_setting('dooplay_home_serial_description', [
		'default' => '',
		'transport' => 'refresh',
	]);
	$customizer->add_setting('dooplay_home_serial_text', [
		'default' => '',
		'transport' => 'refresh',
	]);
	$customizer->add_control(new WP_Customize_Teeny_Control(
		$customizer,
		'dooplay_home_serial_description',
		[
			'label' => __('First screen text'),
			'section' => 'dooplay_child_home',
		]
	));
	$customizer->add_control(new WP_Customize_Teeny_Control(
		$customizer,
		'dooplay_home_serial_text',
		[
			'label' => __('Second text'),
			'section' => 'dooplay_child_home',
		]
	));

}




add_action('wp_head', 'dooplay_child_custom_theme_styles', 100);
function dooplay_child_custom_theme_styles() {
	$background = get_theme_mod('background');
	if ($background) {
		$style = "<style>body #dt_contenedor {background: url($background)top center !important;}</style>";
		echo $style;
	}

}
