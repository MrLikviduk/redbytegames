<?php
    function show_project($name, $pct_url) {
        echo '
            <div class="project" style="background-image: url(\''.$pct_url.'\')">
                <div class="pct-cover">
                    <h2>'.$name.'</h2>
                </div>
            </div>
        ';
    }
?>