<?php
    function show_project($name, $pct_url) {
        echo '
            <a href="/project.php">
                <div class="project" style="background-image: url(\''.$pct_url.'\')">
                    <div class="pct-cover">
                        <h2>'.$name.'</h2>
                    </div>
                </div>
            </a>
        ';
    }
    function show_tech_param($key, $value) {
        echo '
            <div class="property-key">'.$key.'</div>
            <div class="property-value">'.$value.'</div>
            <div style="height: 10px;"></div>
        ';
    }
    function show_paragraph($val, $name, $content) {
        echo '
            <h2 class="paragraph-name" onclick="show_paragraph('.$val.')"><div class="show-paragraph" id="arrow'.$val.'"></div>'.$name.'</h2>
            <hr>
            <p class="paragraph" id="par'.$val.'" style="display: none;">
                '.$content.'
            </p>
        ';
    }
?>