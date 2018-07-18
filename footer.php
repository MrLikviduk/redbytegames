    </main>
    <footer>
        © RedByteGames 2018
        <div class="socnet-wrapper">
            <?=translate('Следите за нами')?>: 
            <a href="https://vk.com/rbg_<?=(isset($_SESSION['lang']) && $_SESSION['lang'] == 'ru' ? 'ru' : 'eng')?>" target="_blank">
                <img src="/img/soc_icons/vkontakte.png" alt="VK" class="socnet">
            </a>
            <a href="https://facebook.com" target="_blank">
                <img src="/img/soc_icons/facebook.png" alt="FB" class="socnet">
            </a>
            <a href="https://twitter.com/games_byte" target="_blank">
                <img src="/img/soc_icons/twitter.png" alt="Twitter" class="socnet">
            </a>
            <a href="https://www.instagram.com/redbytegames/" target="_blank">
                <img src="/img/soc_icons/instagram.png" alt="Instagram" class="socnet">
            </a>
        </div>
    </footer>
</body>

</html>