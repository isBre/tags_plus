<?php
function custom_tag_fields_styles() {
    ?>
    <style>
        .page-description {
            width: 100%;
        }

        .container {
            background-color: #2a2a2a;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;
        }

        .banner {
            width: 125px;
            height: 125px;
            overflow: hidden;
        }

        .banner.player {
            border-radius: 50%;
        }

        .banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .info {
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .info h1 {
            color: white;
            margin: 0;
        }

        .stats {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .boxes {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .info-box {
            padding: 10px 20px 0 20px;
            color: white;
            text-align: center;
            flex: 1;
        }

        .info .info-title {
            font-size: 10px;
            color: #8e8e8e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .info .info-value {
            font-size: 18px;
            color: white;
        }

        @media only screen and (max-width: 992px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .info {
                margin-top: 20px;
            }

            .stats {
                margin-top: 20px;
            }

            .info-box {
                padding: 10px;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'custom_tag_fields_styles');
?>