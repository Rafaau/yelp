try {
    document.querySelectorAll('.reaction-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var reaction = {};
            reaction[this.dataset.reaction] = this.dataset.reactionCount;

            var button = this;

            fetch('/reviews/update_reaction', {
                method: 'POST',
                body: JSON.stringify({
                    id: this.dataset.reviewId,
                    reactions: reaction
                })
            }).then(function(response) {
                if (response.ok) {
                    var reactionCountElement = button.querySelector('.reaction-count');
                    reactionCountElement.textContent = reaction[button.dataset.reaction];
                }
            });
        });
    });
} catch (error) {}

try {
    document.querySelectorAll('.delete-review-btn').forEach(function(button) {
        button.addEventListener('click', function() {

            fetch('/reviews/delete', {
                method: 'POST',
                body: JSON.stringify({
                    id: this.dataset.reviewId,
                })
            }).then(function(response) {
                if (response.ok) {
                    button.closest('.review-element').remove();
                }
            });
        });
    });
} catch (error) {}