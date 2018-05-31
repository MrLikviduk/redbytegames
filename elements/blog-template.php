<?php
    // function delete_blog($id) {
    //     $result = $mysqli->query("SELECT * FROM blog WHERE `id` LIKE $id");
    //     $row = $result->fetch_assoc();

    // }
    function show_blog($header, $content, $date, $tags, $id) {
        echo '
        <article>
            ';
        if ($_SESSION['logged_in'] == TRUE) {
            echo '<img src="/img/edit_blog.png" class="icon-blog edit" id="edit_blog'.$id.'">';
            echo '<img src="/img/del_blog.png" class="icon-blog delete" id="dlt_blog'.$id.'">';
        }
        echo '
            <div class="date-and-tags">
                <span class="date">'.$date.'</span>
                <div class="between-date-and-tags"></div>
                <span class="date">Теги: </span>    ';
        foreach (explode(' ', $tags) as $value) {
            if (trim($value) != '')
                echo '<div class="tag">'.trim($value).'</div>';
        }
        echo '
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