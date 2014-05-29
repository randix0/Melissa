<script type="text/javascript">
    $(document).ready(function(){
        $('[data-type="background"]').each(function(){
            var $bgobj = $(this); // создаем объект
            $(window).scroll(function() {
                var yPos = -($(window).scrollTop() / $bgobj.data('speed')); // вычисляем коэффициент
                // Присваиваем значение background-position
                var coords = 'center '+ yPos + 'px';
                // Создаем эффект Parallax Scrolling
                $bgobj.css({ backgroundPosition: coords });
            });
        });
    });
</script>
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-47734997-1']);
    _gaq.push(['_setDomainName', 'melissa.in.ua']);
    _gaq.push(['_trackPageview']);
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
</body>
</html>