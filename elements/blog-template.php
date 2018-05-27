<?php
    function show_blog($header, $content, $date, $tags) {
        echo '
        <article>
            <div class="date-and-tags">
                <span class="date">'.$date.'</span>
                <div class="tags">
                    ';
        foreach (explode(',', $tags) as $value) {
            echo '<div class="tag">'.$value.'</div>';
        }
        echo '
                </div>
            </div>
            <h1>'.$header.'</h1>
            <div class="line-after-header"></div>
            <br>
            <p>
                '.$content.'
            </p>
        </article>
        ';
    }
?>