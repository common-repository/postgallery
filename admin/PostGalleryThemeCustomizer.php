<?php

namespace Admin;

/**
 * @since 1.0.0
 * @author shennemann
 * @licence MIT
 */
class PostGalleryThemeCustomizer {
    private $sectionId;
    private $fields;
    private $postgalleryAdmin;

    public function __construct() {
        $id = 'postgallery';
        $this->sectionId = $id;

        $this->postgalleryAdmin = PostGalleryAdmin::getInstance();

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


        $this->fields = [];

        $this->fields['postgallery-base'] =
            [
                'title' => __( 'Main-Settings', 'postgallery' ),
                'fields' => [
                    'postgalleryDebugmode' => [
                        'type' => 'checkbox',
                        'label' => __( 'Debug-Mode', 'postgallery' ),
                        'default' => false,
                    ],
                    'sliderType' => [
                        'type' => 'select',
                        'label' => __( 'Slider-Type', 'postgallery' ),
                        'choices' => [
                            'owl' => 'OWL Carousel 2.x',
                            'owl1' => 'OWL Carousel 1.3',
                            'swiper' => 'Swiper (experimental)',
                        ],
                        'default' => 'owl',
                    ],

                    'globalPosition' => [
                        'label' => __( 'Global position', 'postgallery' ),
                        'type' => 'select',
                        'choices' => [
                            'bottom' => __( 'bottom', 'postgallery' ),
                            'top' => __( 'top', 'postgallery' ),
                            'custom' => __( 'custom', 'postgallery' ),
                        ],
                        'default' => defined( 'ELEMENTOR_VERSION' ) ? 'custom' : 'bottom',
                    ],

                    'maxImageWidth' => [
                        'label' => __( 'Max image width', 'postgallery' ),
                        'type' => 'number',
                        'default' => 2560,
                        'description' => 'If uploaded image is bigger, it will be resized'
                    ],

                    'maxImageHeight' => [
                        'label' => __( 'Max image height', 'postgallery' ),
                        'type' => 'number',
                        'default' => 2560,
                        'description' => 'If uploaded image is bigger, it will be resized'
                    ],

                    'disableScripts' => [
                        'type' => 'checkbox',
                        'label' => __( 'Disable scripts loading', 'postgallery' ),
                        'default' => false,
                        'description' => 'Will disable litebox, slider and image-animations',
                    ],
                ],
            ];

        $this->fields['postgallery-templateSettings'] =
            [
                'title' => __( 'Shortcode-Defaults', 'postgallery' ),
                'fields' => [
                    'globalTemplate' => [
                        'label' => __( 'Global template', 'postgallery' ),
                        'type' => 'select',
                        'choices' => array_merge(
                            $this->postgalleryAdmin->getCustomTemplates(),
                            $this->postgalleryAdmin->defaultTemplates
                        ),
                    ],

                    'columns' => [
                        'label' => __( 'Columns', 'postgallery' ),
                        'type' => 'number',
                        'min' => 1,
                        'max' => 0,
                        'default' => 6,
                    ],

                    'noGrid' => [
                        'type' => 'checkbox',
                        'label' => __( 'No Grid', 'postgallery' ),
                        'default' => false,
                    ],

                    'columnGap' => [
                        'label' => __( 'Columns Gap', 'postgallery' ),
                        'type' => 'number',
                        'default' => 0,
                    ],

                    'rowGap' => [
                        'label' => __( 'Rows Gap', 'postgallery' ),
                        'type' => 'number',
                        'default' => 0,
                    ],

                    'thumbWidth' => [
                        'label' => __( 'Thumb width', 'postgallery' ),
                        'type' => 'number',
                        'default' => 150,
                    ],

                    'thumbHeight' => [
                        'label' => __( 'Thumb height', 'postgallery' ),
                        'type' => 'number',
                        'default' => 150,
                    ],
                    'thumbScale' => [
                        'label' => __( 'Thumb scale', 'postgallery' ),
                        'type' => 'select',
                        'default' => '1',
                        'choices' => [
                            '0' => __( 'crop', 'postgallery' ),
                            '1' => __( 'long edge', 'postgallery' ),
                            '2' => __( 'short edge', 'postgallery' ),
                            '3' => __( 'ignore proportions', 'postgallery' ),
                        ],
                    ],

                    'useSrcset' => [
                        'type' => 'checkbox',
                        'label' => __( 'Responsive image size (srcset)', 'postgallery' ),
                        'default' => false,
                    ],

                    'imageViewportWidth' => [
                        'label' => __( 'Image width in viewport', 'postgallery' ),
                        'type' => 'text',
                        'default' => 150,
                    ],

                    // design
                    'masonry' => [
                        'label' => __( 'Masonry', 'postgallery' ),
                        'type' => 'select',
                        'default' => 0,
                        'choices' => [
                            0 => __( 'off' ),
                            'on' => __('on' ),
                            'horizontal' => 'horizontal order',
                            'css' => 'CSS only',
                        ],
                    ],

                    'equalHeight' => [
                        'label' => __( 'Equal height', 'postgallery' ),
                        'type' => 'checkbox',
                        'default' => false,
                        'return_value' => 'on',
                    ],

                    'itemRatio' => [
                        'label' => __( 'Item Ratio', 'postgallery' ),
                        'type' => 'range',
                        'default' => [
                            'size' => 0.66,
                        ],

                        'input_attrs' => [
                            'min' => 0.1,
                            'max' => 2,
                            'step' => 0.01,
                        ],
                    ],

                    // extended options
                    'sliderOwlConfig' => [
                        'type' => 'textarea',
                        'label' => __( 'Owl-Slider-Config (for Slider-Template)', 'postgallery' ),
                        'default' => "items: 1,\nnav: 1,\ndots: 1,\nloop: 1,",
                    ],

                    'stretchImages' => [
                        'label' => __( 'Stretch small images (for watermark)', 'postgallery' ),
                        'type' => 'checkbox',
                    ],
                ],
            ];

        $this->fields['postgallery-liteboxAnimation'] = [
            'title' => __( 'Animation', 'postgallery' ),

            'fields' => [
                // image animation
                'imageAnimation' => [
                    'type' => 'checkbox',
                    'label' => __( 'Image Animation', 'postgallery' ),
                    'default' => false,
                ],
                'imageAnimationDuration' => [
                    'type' => 'number',
                    'label' => __( 'Animation Duration', 'postgallery' ),
                    'default' => 300,
                ],
                'imageAnimationTimeBetween' => [
                    'type' => 'number',
                    'label' => __( 'Time between images', 'postgallery' ),
                    'default' => 200,
                ],
                'imageAnimationCss' => [
                    'type' => 'textarea',
                    'label' => __( 'Custom-CSS for Image', 'postgallery' ),
                    'default' => '',
                ],
                'imageAnimationCssAnimated' => [
                    'type' => 'textarea',
                    'label' => __( 'Custom-CSS for animated Image', 'postgallery' ),
                    'default' => '',
                ],

                'slideSpeed' => [
                    'id' => 'slideSpeed',
                    'label' => __( 'Speed (ms)', 'postgallery' ),
                    'type' => 'number',
                    'datasrc' => 'moduldata',
                    //'tooltip' => 'Gibt an wie lange die Animation eines Slides dauert.'
                ],

                'autoplay' => [
                    'id' => 'autoplay',
                    'label' => 'Autoplay',
                    'type' => 'checkbox',
                    'datasrc' => 'moduldata',
                    //'description' => 'Slider wechselt automatisch die Bilder.',
                ],
                'autoplayTimeout' => [
                    'id' => 'autoplayTimeout',
                    'label' => __( 'Autoplay timeout (ms)', 'postgallery' ),
                    'type' => 'number',
                    'placeholder' => 5000,
                    'datasrc' => 'moduldata',
                    //'description' => 'Gibt an wie lange ein Item angezeigt wird und bis die nächste Animation beginnt.'
                ],
                'animateOut' => [
                    'id' => 'animateOut',
                    'label' => __( 'Animate out', 'postgallery' ),
                    'type' => 'select',
                    'choices' => $sliderAnimations,
                    'datasrc' => 'moduldata',
                    //'description' => 'Gibt die Animation an mit welcher ein Item ausgeblendet wird',
                ],

                'animateIn' => [
                    'id' => 'animateIn',
                    'label' => __( 'Animate in', 'postgallery' ),
                    'type' => 'select',
                    'choices' => $sliderAnimations,
                    'datasrc' => 'moduldata',
                    //'description' => 'Gibt die Animation an mit welcher ein Item eingeblendet wird<br />'
                    //.'Look <a target="_blank" href="https://daneden.github.io/animate.css/">Animate.css</a>',
                ],
            ],
        ];

        $this->fields['postgallery-liteboxSettings'] =
            [
                'title' => __( 'Litebox-Settings', 'postgallery' ),
                'fields' => [
                    'enableLitebox' => [
                        'type' => 'checkbox',
                        'label' => __( 'Enable', 'postgallery' ) . ' Litebox',
                        'default' => true,
                    ],
                    'liteboxTemplate' => [
                        'type' => 'select',
                        'default' => 'default',
                        'label' => __( 'Litebox-Template', 'postgallery' ),
                        'choices' => $this->postgalleryAdmin->getLiteboxTemplates(),
                    ],

                    'owlTheme' => [
                        'type' => 'text',
                        'default' => 'default',
                        'label' => __( 'Owl-Theme', 'postgallery' ),
                        'input_attrs' => [ 'list' => 'postgallery-owl-theme' ],
                        'description' => '<datalist id="postgallery-owl-theme"><option>default</option><option>green</option></datalist>',
                    ],
                    'clickEvents' => [
                        'type' => 'checkbox',
                        'label' => __( 'Enable Click-Events', 'postgallery' ),
                        'default' => true,
                    ],
                    'keyEvents' => [
                        'type' => 'checkbox',
                        'label' => __( 'Enable Keypress-Events', 'postgallery' ),
                        'default' => true,
                    ],
                    'arrows' => [
                        'type' => 'checkbox',
                        'label' => __( 'Show arrows', 'postgallery' ),
                        'default' => false,
                    ],
                    'asBg' => [
                        'type' => 'checkbox',
                        'label' => __( 'Images as Background', 'postgallery' ),
                        'default' => false,
                    ],

                    'items' => [
                        'id' => 'items',
                        'label' => 'Items',
                        'type' => 'number',
                        'default' => 1,
                    ],

                    'mainColor' => [
                        'type' => 'text',
                        'label' => __( 'Main-Color', 'postgallery' ),
                        'default' => '#fff',
                    ],
                    'secondColor' => [
                        'type' => 'text',
                        'label' => __( 'Second-Color', 'postgallery' ),
                        'default' => '#333',
                    ],

                    'owlConfig' => [
                        'type' => 'textarea',
                        'label' => __( 'Owl-Litebox-Config', 'postgallery' ),
                        'default' => '',
                    ],

                    'owlThumbConfig' => [
                        'type' => 'textarea',
                        'label' => __( 'Owl-Config for Thumbnail-Slider', 'postgallery' ),
                        'description' => '<b>' . __( 'Presets', 'postgallery' ) . '</b>:'
                            . '<select class="owl-slider-presets">
                                <option value="">Slide (' . __( 'Default', 'postgallery' ) . ')</option>
                                <option value="fade">Fade</option>
                                <option value="slidevertical">SlideVertical</option>
                                <option value="zoominout">Zoom In/out</option>
                                </select>',
                    ],

                    'owlDesc' => [
                        'type' => 'hidden',
                        'label' => __( 'Description', 'postgallery' ),
                        'description' => __( 'You can use these options', 'postgallery' ) . ':<br />' .
                            '<a href="https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html" target="_blank">
							OwlCarousel Options
						</a>
						<br />' .
                            __( 'You can use these animations', 'postgallery' ) . ':<br />
						<a href="http://daneden.github.io/animate.css/" target="_blank">
							Animate.css
						</a>
					</div>',
                    ],
                ],
            ];
    }

    public function actionCustomizeRegister( $wp_customize ) {
        $prefix = 'postgallery_';
        $wp_customize->add_panel( 'postgallery-panel', [
            'title' => __( 'PostGallery' ),
            'section' => 'postgallery',
        ] );


        foreach ( $this->fields as $sectionId => $section ) {
            $wp_customize->add_section( $sectionId, [
                'title' => $section['title'],
                'panel' => 'postgallery-panel',
            ] );

            foreach ( $section['fields'] as $fieldId => $field ) {
                $settingId = $prefix . ( !is_numeric( $fieldId ) ? $fieldId : $field['id'] );
                $controlId = $settingId . '-control';

                $wp_customize->add_setting( $settingId, [
                    'default' => !empty( $field['default'] ) ? $field['default'] : '',
                    'transport' => !empty( $field['transport'] ) ? $field['transport'] : 'refresh',
                ] );

                $wp_customize->add_control( $controlId, [
                    'label' => $field['label'],
                    'section' => $sectionId,
                    'type' => !empty( $field['type'] ) ? $field['type'] : 'text',
                    'settings' => $settingId,
                    'description' => !empty( $field['description'] ) ? $field['description'] : '',
                    'choices' => !empty( $field['choices'] ) ? $field['choices'] : null,
                    'input_attrs' => !empty( $field['input_attrs'] ) ? $field['input_attrs'] : null,
                ] );
            }
        }
    }
}

