const continueBtn = $('#continueBtn');

continueBtn.on('click', async () => {
    continueBtn.addClass('loading');

    window.location.href = window.loginRedirectURL;
});
