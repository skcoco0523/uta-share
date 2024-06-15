<div id="shareModal" class="notification-overlay" onclick="closeShareModal(event)">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="share-icons">
            <i class="fa-brands fa-line icon-50" style="color: #00c34d;" alt="LINEで共有" onclick="shareToPlatform('line', '{{ request()->fullUrl() }}')"></i>
            <i class="fa-brands fa-x-twitter icon-50" style="color: #001c40;" alt="Twitterで共有" onclick="shareToPlatform('twitter', '{{ request()->fullUrl() }}')"></i>
            <i class="fa-brands fa-facebook icon-50" style="color: #0863f7;" alt="Facebookで共有" onclick="shareToPlatform('facebook', '{{ request()->fullUrl() }}')"></i>
        </div>
    </div>
</div>