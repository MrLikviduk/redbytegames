<?php
    function show_vacancy($name, $type, $date) {
        echo '
            <div class="vacancy">
                <div class="type">
                    '.$type.'
                </div>
                <div class="name">
                    '.$name.'
                </div>
                <div class="date">
                    Опубликовано: '.$date.'
                </div>
            </div>
        ';
    }
?>