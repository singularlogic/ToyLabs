<template>
    <div class="ui basic modal">
        <div class="ui icon header">
            <i class="icon" :class="icon"></i> {{ title }}
        </div>
        <div class="content">
            <p>{{ body }}</p>
        </div>
        <div class="actions">
            <div class="ui red basic cancel inverted button">
                <i class="remove icon"></i> No
            </div>
            <div class="ui green ok inverted button">
                <i class="checkmark icon"></i> Yes
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['title', 'body', 'icon'],
}
</script>