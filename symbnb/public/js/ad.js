
    $('#add-image').click(function () {
        //Calculating future created fields
        const index = +$('#widgets-counter').val();
    console.log(index);
    //Taking entrys prototype
    const template = $('#ad_images').data('prototype').replace(/__name__/g, index);

    // Adding template on the div
    $('#ad_images').append(template);

    $('#widgets-counter').val(index + 1);

    //Delete button
    handleDeleteButtons();
});

    function handleDeleteButtons() {
        $('button[data-action="delete"]').click(function () {
            const target = this.dataset.target;
            $(target).remove();
        });
    }
    function uptdateCounter() {
        const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}
uptdateCounter();
handleDeleteButtons();
