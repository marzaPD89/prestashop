{foreach from=$cssfiles item=css}
    <link type="text/css" rel="stylesheet" href="{$path}css/{$css}" />
{/foreach}
{foreach from=$jsfiles item=js}
   <script type="text/javascript" src="{$path}js/{$js}" /></script>
{/foreach}