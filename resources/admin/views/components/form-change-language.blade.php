<div class="change-language">
    <div class="change-language-row">
        <div class="change-language-row-item" :class="{ 'active': locale == km }" @click="locale = km">
            <span>Khmer</span>
        </div>
        <div class="change-language-row-item" :class="{ 'active': locale == en }" @click="locale = en">
            <span>English</span>
        </div>
    </div>
</div>
