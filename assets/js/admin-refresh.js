jQuery(function ($) {

    $(document).on("click", ".flashpack-refresh-cache-btn", function (e) {
        e.preventDefault();

        const $btn = $(this);
        const $spinner = $btn.next(".flashpack-spinner");

        const nonce = $btn.data("nonce");
        const scrollPos = $(window).scrollTop();

        $btn.css({ opacity: 0.5, "pointer-events": "none" });
        $spinner.show();

        $.post(FlashPackRefresh.ajax_url, {
            action: "flashpack_refresh_cache",
            _ajax_nonce: nonce
        })
        .done(function (response) {
            if (response.success) {
                flashpackNotice("success", response.data.message);
            } else {
                flashpackNotice("error", response.data.message || "Erro inesperado.");
            }
        })
        .fail(function () {
            flashpackNotice("error", "Falha na requisição AJAX.");
        })
        .always(function () {
            $spinner.hide();
            $btn.css({ opacity: 1, "pointer-events": "auto" });
            setTimeout(() => $(window).scrollTop(scrollPos), 30);
        });
    });

    function flashpackNotice(type, message) {
        const css = type === "success" ? "notice-success" : "notice-error";

        const $notice = $(`
            <div class="notice ${css} is-dismissible">
                <p>${message}</p>
            </div>
        `);

        $(".wrap h1").first().after($notice);

        setTimeout(() => $notice.fadeOut(), 6000);
    }
});
