<?php
namespace Admin;

use Lib\PostGallery;
use Lib\Thumb;


class SliderShortcodeAdmin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $pluginName The ID of this plugin.
     */
    private $pluginName;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin..
     */
    private $version;



    private $optionFields;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $pluginName The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct( $pluginName, $version ) {
        $this->pluginName = $pluginName;
        $this->version = $version;


        // slide animations from animate.css
        $sliderAnimations = explode( ',', 'bounce,	flash,	pulse,	rubberBand,
shake,	headShake,	swing,	tada,
wobble,	jello,	bounceIn,	bounceInDown,
bounceInLeft,	bounceInRight,	bounceInUp,	bounceOut,
bounceOutDown,	bounceOutLeft,	bounceOutRight,	bounceOutUp,
fadeIn,	fadeInDown,	fadeInDownBig,	fadeInLeft,
fadeInLeftBig,	fadeInRight,	fadeInRightBig,	fadeInUp,
fadeInUpBig,	fadeOut,	fadeOutDown,	fadeOutDownBig,
fadeOutLeft,	fadeOutLeftBig,	fadeOutRight,	fadeOutRightBig,
fadeOutUp,	fadeOutUpBig,	flipInX,	flipInY,
flipOutX,	flipOutY,	lightSpeedIn,	lightSpeedOut,
rotateIn,	rotateInDownLeft,	rotateInDownRight,	rotateInUpLeft,
rotateInUpRight,	rotateOut,	rotateOutDownLeft,	rotateOutDownRight,
rotateOutUpLeft,	rotateOutUpRight,	hinge,	jackInTheBox,
rollIn,	rollOut,	zoomIn,	zoomInDown,
zoomInLeft,	zoomInRight,	zoomInUp,	zoomOut,
zoomOutDown,	zoomOutLeft,	zoomOutRight,	zoomOutUp,
slideInDown,	slideInLeft,	slideInRight,	slideInUp,
slideOutDown,	slideOutLeft,	slideOutRight,	slideOutUp' );
        array_unshift( $sliderAnimations, '' );

        // need as key-value pair
        $sliderAnimationsKeyValue = [];
        foreach ( $sliderAnimations as $value ) {
            $sliderAnimationsKeyValue[trim( $value )] = trim( $value );
        }
        $sliderAnimations = $sliderAnimationsKeyValue;

        $this->optionFields = [

            'headline_base' => [
                'type' => 'headline',
                'label' => 'Base-Settings',
            ],
            'sliderType' => [
                'type' => 'select',
                'label' => __( 'Type', 'postgallery' ),
                'options' => [
                    'owl' => 'owl',
                    'swiper' => 'swiper (experimental)'
                ],
            ],

            'sliderWidth' => [
                'type' => 'text',
                'label' => __( 'Width', 'postgallery' ),
            ],
            'sliderHeight' => [
                'type' => 'text',
                'label' => __( 'Height', 'postgallery' ),
            ],
            'sliderScale' => [
                'type' => 'select',
                'label' => __( 'Image-Scale', 'postgallery' ),
                'options' => [
                    0 => __( 'crop', 'postgallery' ),
                    1 => __( 'Keep proportions (long edge)', 'postgallery' ),
                    2 => __( 'Keep proportions (short edge)', 'postgallery' ),
                    3 => __( 'Ignore proportions', 'postgallery' ),
                ],
            ],
            'sliderItems' => [
                'type' => 'number',
                'label' => __( 'Items', 'postgallery' ),
                'default' => 3,
            ],
            'sliderLoop' => [
                'type' => 'checkbox',
                'label' => __( 'Loop', 'postgallery' ),
                'default' => true,
            ],

            // images
            'headline_image' => [
                'type' => 'headline',
                'label' => 'Image-Settings',
            ],
            'sliderImageWidth' => [
                'type' => 'text',
                'label' => __( 'Image-Width', 'postgallery' ),
            ],
            'sliderImageHeight' => [
                'type' => 'text',
                'label' => __( 'Image-Height', 'postgallery' ),
            ],
            'sliderAsBg' => [
                'type' => 'checkbox',
                'label' => __( 'Images as Background', 'postgallery' ),
            ],

            'sliderLoadFrom' => [
                'type' => 'select',
                'label' => __( 'Load images from', 'postgallery' ),
                'options' => '',
                'multiple' => true,
            ],
            'sliderThumbOnly' => [
                'type' => 'checkbox',
                'label' => __( 'Show only Post-Thumbs', 'postgallery' ),
            ],

            // animation
            'headline_animation' => [
                'type' => 'headline',
                'label' => 'Animation',
            ],
            'slideSpeed' => [
                'id' => 'slideSpeed',
                'label' => 'Speed (ms)',
                'type' => 'number',
                'datasrc' => 'moduldata',
                //'tooltip' => 'Gibt an wie lange die Animation eines Slides dauert.'
            ],

            'sliderAutoplay' => [
                'type' => 'checkbox',
                'label' => __( 'Autoplay', 'postgallery' ),
            ],

            'autoplayTimeout' => [
                'id' => 'autoplayTimeout',
                'label' => 'Autoplay timeout (ms)',
                'type' => 'number',
                'placeholder' => 5000,
                'datasrc' => 'moduldata',
                //'description' => 'Gibt an wie lange ein Item angezeigt wird und bis die nächste Animation beginnt.'
            ],
            'animateOut' => [
                'id' => 'animateOut',
                'label' => 'Animate out',
                'type' => 'select',
                'options' => $sliderAnimations,
                'datasrc' => 'moduldata',
                //'description' => 'Gibt die Animation an mit welcher ein Item ausgeblendet wird',
            ],

            'animateIn' => [
                'id' => 'animateIn',
                'label' => 'AnimateIn',
                'type' => 'select',
                'options' => $sliderAnimations,
                'datasrc' => 'moduldata',
                //'description' => 'Gibt die Animation an mit welcher ein Item eingeblendet wird<br />'
                //.'Look <a target="_blank" href="https://daneden.github.io/animate.css/">Animate.css</a>',
            ],

            // extra settings
            'headline_extra' => [
                'type' => 'headline',
                'label' => 'Extra-Settings',
            ],
            'sliderShuffle' => [
                'type' => 'checkbox',
                'label' => __( 'Shuffle images', 'postgallery' ),
            ],
            'sliderLinkPost' => [
                'type' => 'checkbox',
                'label' => __( 'Link images with post', 'postgallery' ),
            ],
            'sliderOwlConfig' => [
                'type' => 'textarea',
                'label' => __( 'Owl-Config', 'postgallery' ),
                /*'descTop' => '<b>' . __( 'Presets', 'postgallery' ) . '</b>:'
                    . '<select class="owl-slider-presets" data-lang="' . get_locale() . '" data-container="sliderOwlConfig">
                    <option value="">Slide (' . __( 'Default', 'postgallery' ) . ')</option>
                    <option value="fade">Fade</option>
                    <option value="slidevertical">SlideVertical</option>
                    <option value="zoominout">Zoom In/out</option>
                    </select>',*/

                'desc' => '<br />' . __( 'You can use these options', 'postgallery' ) . ':<br />' .
                    '<a href="https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html" target="_blank">
                                        OwlCarousel Options
                                    </a>
                                    <br />' .
                    __( 'You can use these animations', 'postgallery' ) . ':<br />
                                    <a href="http://daneden.github.io/animate.css/" target="_blank">
                                        Animate.css
                                    </a>
                                </div>',
                'inputClass' => 'owl-post-config',
                'cols' => 50,
                'rows' => 10,
                'default' => '',
            ],
            'sliderNoLazy' => [
                'type' => 'checkbox',
                'label' => __( 'Disable lazy load', 'postgallery' ),
            ],
        ];

        add_action( 'init', [ $this, '_createPostTypes' ] );
        add_action( 'add_meta_boxes', [ $this, '_registerPostSettings' ] );
        add_action( 'save_post', [ $this, 'savePostMeta' ], 10, 2 );
    }

    /**
     * Fills the array with option-fields
     * Needs to be filled after all post-types are registered
     */
    public function _setPostList() {
        $postTypes = get_post_types();
        unset( $postTypes['revision'] );
        unset( $postTypes['nav_menu_item'] );

        $postlist = [ '' ];

        $exclude = [ $GLOBALS['post']->ID ];

        // get categories
        $allCategories = get_categories();

        foreach ( $allCategories as $category ) {
            $postlist['cat-' . $category->cat_ID] = $category->name;

            // get posts from category
            $catPosts = get_posts( [
                'post_type' => $postTypes,
                'category' => $category->cat_ID,
                'exclude' => $GLOBALS['post']->ID,
                'posts_per_page' => -1,
            ] );

            foreach ( $catPosts as $wPost ) {
                $postlist[$wPost->ID] = '&nbsp;&nbsp;' . $wPost->post_title;
                $exclude[] = $wPost->ID;
            }
        }

        $remainingPosts = get_posts( [
            'post_type' => $postTypes,
            'exclude' => $exclude,
            'posts_per_page' => -1,
        ] );

        foreach ( $remainingPosts as $wPost ) {
            $postlist[$wPost->ID] = ' ' . $wPost->post_title;
        }

        $this->optionFields['sliderLoadFrom']['options'] = $postlist;
    }

    /**
     * Create slider post-type
     */
    public function _createPostTypes() {
        register_post_type( 'postgalleryslider', [
                'labels' => [
                    'name' => __( 'Slider', 'postgallery' ),
                    'singular_name' => __( 'Slider', 'postgallery' ),
                ],
                'menu_icon' => 'dashicons-images-alt',
                'public' => true,
                'has_archiv' => false,
                'show_ui' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'supports' => [ 'title' ],
                'exclude_from_search' => true,
                'publicly_queryable' => true,
                'excerpt' => false,
            ]
        );
    }

    /**
     * Register meta-boxes
     *
     * @return bool
     */
    public function _registerPostSettings() {
        add_meta_box( 'post-gallery-slider-shortcode', __( 'Slider-Shortcode', 'postgallery' ), [ $this, '_addSliderShortcodeView' ], 'postgalleryslider' );
        add_meta_box( 'post-gallery-slider-settings', __( 'Slider-Settings', 'postgallery' ), [ $this, '_addSliderSettings' ], 'postgalleryslider' );

        return false;
    }

    /**
     * Show a metabox with the shortcode for slider
     *
     * @param $post
     */
    public function _addSliderShortcodeView( $post ) {
        echo '<input type="text" value="[slider ' . $post->ID . ']" onClick="this.select();" /> &nbsp;';
        echo '<a href="https://github.com/RTO-Websites/post-gallery/blob/master/docs/slider.md" target="_blank">Slider Documentation</a><br />';
    }

    /**
     * Add meta-box with slider-options
     *
     * @param $post
     */
    public function _addSliderSettings( $post ) {
        $this->_setPostList();
        $this->createFields( $post, $this->optionFields );
    }

    /**
     * Creates fields from property optionFields
     */
    private function createFields( $post, $fields ) {
        echo '<table class="form-table">';

        if ( !empty( $fields ) ) {
            // Loop Post-Options and generate inputs
            foreach ( $fields as $key => $option ) {
                $trClass = !empty( $option['trClass'] ) ? $option['trClass'] : '';
                $inputClass = !empty( $option['inputClass'] ) ? $option['inputClass'] : '';

                $value = get_post_meta( $post->ID, $key, true );

                if ( !empty( $option['isJson'] ) ) {
                    $value = json_encode( $value );
                }

                echo '<tr valign="top" class="input-type-' . $option['type'] . ' ' . $trClass . '">';
                // Generate Label
                echo '<th scope="row"><label class="field-label" for="' . $key . '">' . $option['label'] . '</label></th>';
                echo '<td>';

                if ( !empty( $option['descTop'] ) ) {
                    echo $option['descTop'] . '<br />';
                }

                switch ( $option['type'] ) {
                    case 'headline':
                        //echo '<h2 class="title">' . $option['label'] . '</h2>';
                        break;
                    case 'select':
                        // Generate select
                        $multiple = !empty( $option['multiple'] ) ? ' multiple ' : '';
                        $selectKey = !empty( $option['multiple'] ) ? $key . '[]' : $key;
                        echo '<select class="field-input" name="' . $selectKey . '" ' . $inputClass . ' id="' . $key . '" ' . $multiple . '>';
                        if ( !empty( $option['options'] ) && is_array( $option['options'] ) ) {
                            foreach ( $option['options'] as $optionKey => $optionTitle ) {
                                $selected = '';
                                if ( $optionKey == $value ||
                                    is_array( $value ) && in_array( $optionKey, $value )
                                ) {
                                    $selected = ' selected="selected"';
                                }
                                echo '<option value="' . $optionKey . '"' . $selected . '>' . $optionTitle . '</option>';
                            }
                        }
                        echo '</select>';
                        break;

                    case 'input':
                        // Generate text-input
                        echo '<input class="field-input ' . $inputClass . '" type="text" name="' . $key . '" id="' . $key . '" value="'
                            . $value . '" />';
                        break;

                    case 'checkbox':
                        // Generate checkbox
                        echo '<input class="field-input ' . $inputClass . '" type="checkbox" name="' . $key . '" id="' . $key . '" value="1" '
                            . ( !empty( $value ) ? 'checked="checked"' : '' ) . '" />';
                        break;

                    case 'textarea':
                        // Generate textarea
                        $cols = !empty( $option['cols'] ) ? ' cols="' . $option['cols'] . '"' : '';
                        $rows = !empty( $option['rows'] ) ? ' rows="' . $option['rows'] . '"' : '';
                        echo '<textarea class="field-input ' . $inputClass . '" name="' . $key . '" id="' . $key . '" ' . $rows . $cols . '>'
                            . $value .
                            '</textarea>';
                        break;

                    case 'background':
                        echo '<span class="sublabel">' . __( 'Image', 'postgallery' ) . '</span>
                                <input class="field-input ' . $inputClass . ' upload-field" type="hidden" name="' . $key . '-image" id="'
                            . $key . '-image" value=\''
                            . $value . '\' />
                            <input class="field-button ' . $inputClass . ' upload-button" type="button" name="' . $key . '-image-button" id="'
                            . $key . '-image-button" value=\''
                            . __( 'Select image', 'postgallery' ) . '\' />
                            <img src="" alt="" id="' . $key . '-image-img" class=" upload-preview-image" />
                            <br />';
                        echo '<span class="sublabel">' . __( 'Color', 'postgallery' ) . '</span>
                                <input class="field-input ' . $inputClass . '" type="text" name="' . $key . '-color" id="'
                            . $key . '-color" value=\''
                            . $value . '\' /><br />';
                        echo '<span class="sublabel">Repeat</span>
                                <input class="field-input ' . $inputClass . '" type="text" name="' . $key . '-repeat" id="'
                            . $key . '-repeat" value=\''
                            . $value . '\' /><br />';
                        echo '<span class="sublabel">Position</span>
                                <input class="field-input ' . $inputClass . '" type="text" name="' . $key . '-position" id="'
                            . $key . '-position" value=\''
                            . $value . '\' /><br />';
                        echo '<span class="sublabel">Size</span>
                                <input class="field-input ' . $inputClass . '" type="text" name="' . $key . '-size" id="'
                            . $key . '-size" value=\''
                            . $value . '\' /><br />';
                        break;

                    case 'hidden':
                    case 'number':
                    case 'text':
                        // Generate text-input
                        echo '<input class="field-input ' . $inputClass . '" type="' . $option['type'] . '" name="' . $key . '" id="' . $key . '" value=\''
                            . $value . '\' />';
                        break;
                }
                if ( !empty( $option['desc'] ) ) {
                    echo '<br />' . $option['desc'];
                }

                echo '</td></tr>';
            }
        }

        echo '</table>';
    }

    /**
     * Method to save Post-Meta
     *
     * @global type $post_options
     * @param type $postId
     * @param type $post
     * @return type
     */
    public function savePostMeta( $postId, $post ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( !filter_has_var( INPUT_POST, 'post_type' ) ) {
            return;
        }

        $postType = filter_input( INPUT_POST, 'post_type' );
        if ( $postType == 'page' ) {
            if ( !current_user_can( 'edit_page', $postId ) ) {
                return;
            }
        } else {
            if ( !current_user_can( 'edit_post', $postId ) ) {
                return;
            }
        }

        // Save form-fields
        if ( !empty( $this->optionFields ) ) {
            foreach ( $this->optionFields as $key => $postOption ) {
                if ( !filter_has_var( INPUT_POST, $key ) ) {
                    #continue;
                }

                if ( isset( $_POST[$key] ) && is_array( $_POST[$key] ) ) {
                    // multiselect
                    $value = [];
                    foreach ( $_POST[$key] as $aKey => $aValue ) {
                        $value[] = filter_var( $aValue );
                    }
                } else {
                    // single field
                    $value = filter_input( INPUT_POST, $key );
                }

                if ( !empty( $postOption['isJson'] ) ) {
                    $value = json_decode( $value );
                }

                update_post_meta( $postId, $key, $value );

                #echo '<br>'.$key.':'.$value;
            }

            #exit();
        }
    }
}
