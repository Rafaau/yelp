<script lang='ts'>
    import { currentUser } from "../../store";

    export let review: any;
    
    let reactions = {
        'useful': 'fa-lightbulb',
        'funny': 'fa-face-laugh-squint',
        'cool': 'fa-face-grin-wink'
    }

    function capitalize(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function updateReactions(reviewId, reaction, count) {
        if ($currentUser != null) {
            review.reactions[reaction] += 1;
            fetch('/reviews/update_reaction', {
                method: 'POST',
                body: JSON.stringify({
                    id: reviewId,
                    reactions: review.reactions
                })
            }).then(function(response) {
                if (response.ok) {
                    let reactionContainer = document.querySelector(`[data-review-id="${reviewId}"][data-reaction="${reaction}"]`);
                    let reactionCount = reactionContainer.querySelector('.reaction-count');
                    reactionCount.innerHTML = `${count + 1}`;
                }
            });
        }
    }
</script>

{#each Object.entries(reactions) as [reaction, icon]}
    <button 
        data-review-id="{review.id}" 
        data-reaction="{reaction}"
        data-reaction-count="{review.reactions[reaction] + 1 }"
        class="py-1 px-2 border border-zinc-400 rounded flex items-center reaction-btn hover:bg-zinc-200 w-full text-xs"
        on:click={() => updateReactions(review.id, reaction, review.reactions[reaction])}>
        <i class="fa-regular {icon} mr-2"></i>
        {capitalize(reaction)}
        <span class="font-roboto-light ml-2 reaction-count">
            {review.reactions[reaction]}
        </span>
    </button>
{/each}
