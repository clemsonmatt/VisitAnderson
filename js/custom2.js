

$(function() {

    /*** add/edit questions ***/
    var answerCount = 1;

    answerCount = Answer.add(answerCount);

    /* add answer */
    $('#add-button').on('click', function(e) { answerCount = Answer.add(answerCount) });

    /* remove answer */
    $('#input-list').on('click', '.remove-answer', function(e) { Answer.remove($(this).val(), answerCount) });
    $('.remove-answer').on('click', function(e) { Answer.remove($(this).val(), answerCount) });
})


var Answer = {
    /* add an answer to a question */
    add: function(answerCount) {
        /* add remove button */
        var newButton = $(
            '<div  id="remove-btn-' + answerCount + '">'
                + '<input id="input-' + answerCount + '">'
                + '<button class="btn btn-danger remove-answer" value="' + answerCount + '" type="button" style="margin-bottom: 15px;">'
                    + '<i class="fa fa-remove"></i>'
                + '</button><br>'
            + '</div>'
        );
        $('#input-list').append(newButton);

        answerCount++;

        return answerCount;
    },

    /* remove an answer to the question */
    remove: function(id, answerCount) {
        $('#add_question_answers_' + id).remove();
        $('#add_question_answers_' + id + '_title').remove();
        $('#remove-btn-' + id).remove();
    }
}
