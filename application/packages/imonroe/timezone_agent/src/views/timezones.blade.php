
<script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script src="https://momentjs.com/downloads/moment-timezone-with-data.js"></script>

<script type="text/javascript">
    var tz_guess = moment.tz.guess();
    console.log( 'I guess your timezone is: ' + tz_guess );
    document.cookie = 'coldreader_timezone='+tz_guess;
</script>