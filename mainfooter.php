<div class="bg-teal-950 w-full relative left-0">
        <p class="text-slate-300 text-center font-medium text-md py-3">@Copyrights Richie's Project ALP Webprog</p>
    </div>
    <script>
        $(document).ready(function () {
            $('.accordion-header').on('click', function () {
                $(this).toggleClass('active');
                const accordionBody = $(this).next();
                if ($(this).hasClass('active')) {
                    accordionBody.css('max-height', accordionBody.prop('scrollHeight') + "px");
                } else {
                    accordionBody.css('max-height', 0);
                }
            });
        });
    </script>
</body>

</html>