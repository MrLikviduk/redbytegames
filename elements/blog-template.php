<?php
    function show_blog($header, $content, $date, $tags, $id) {
        echo '
        <article>
            ';
        if (TRUE) {
            echo '<button class="delete_blog" name="dlt_blog'.$id.'"></button>'
        }
        echo '
            <div class="date-and-tags">
                <span class="date">'.$date.'</span>
                <div class="tags">
                    ';
        foreach (explode(',', $tags) as $value) {
            echo '<div class="tag">'.trim($value).'</div>';
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