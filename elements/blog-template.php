<?php
    // function delete_blog($id) {
    //     $result = $mysqli->query("SELECT * FROM blog WHERE `id` LIKE $id");
    //     $row = $result->fetch_assoc();

    // }
    function show_blog($header, $content, $date, $tags, $id) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
        echo '
        <article>
            ';
        if (can_do('edit_blog')) {
            echo '<form action="" method="post">
                    <button class="icon-blog edit" name="edit_blog" value="'.$id.'"></button>
                    <button class="icon-blog delete" name="delete_blog" value="'.$id.'"></button>
                </form>
                ';
            
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