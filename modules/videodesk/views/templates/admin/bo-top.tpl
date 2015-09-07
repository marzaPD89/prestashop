<script type="text/javascript">
    $(document).ready(function(){
        $('#backofficetop').slideDown('slow', function() {
            setTimeout(hideMessage, 2500);
        });
        
        function hideMessage () {
            $('#backofficetop').slideUp('slow', function() {
                //$(this).hide();
            });
        }
    });
</script>

<style>
    #backofficetop{
        background-color: #FFF1A8;
        border: 1px solid #FFD96F;
        border-radius: 0 0 3px 3px;
        color: #000000;
        display: none;
        font-size: 12px;
        font-weight: bold;
        left: 40%;
        padding: 5px 20px;
        position: fixed;
        top: 0;
        width: 300px;
        z-index: 10000;
        text-align:center;
    }
</style>

<div style="" id="backofficetop">
    {if $errors}
        <p style="color:red;">{l s='Error during downloading Videodesk historic files.' mod='videodesk'}</p>
    {else}
        <p style="color:green;">{l s='Videodesk historic updated.' mod='videodesk'}</p>
    {/if}
</div>