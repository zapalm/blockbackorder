{**
* Block Back Order: module for PrestaShop 1.2-1.6
*
* @author      zapalm <zapalm@ya.ru>
* @copyright   (c) 2011-2016, zapalm
* @link        http://prestashop.modulez.ru/en/ Homepage
* @license     http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
*}

<!-- MODULE blockbackorder -->
<script type="text/javascript">
// <![CDATA[
    {literal}
        $("document").ready(function () {
            $("#add_to_cart").hide();
            $("#bo_div").show();
        });
    {/literal}
//]]>
</script>

{literal}
    <STYLE TYPE="text/css">
        <!--
        div.bo_title {
            color: #374853;
            font-size: 1.1em;
            font-weight: bold;
            height: 21px;
            line-height: 1.6em;
            margin: 0.5em 0;
            padding-left: 0.5em;
            text-transform: uppercase;
        }

        div.bo_description {
            font-size: 1em;
            font-weight: bold;
            margin-left: 6px;
            padding-bottom: 20px;
        }

        label.bo_label {
            float: left;
            padding: 0.2em 0 0;
            text-align: left;
            width: 76px;
            margin-left: 6px;
        }

        div.bo_div {
            padding-bottom: 10px;
            padding-top: 10px;
            display: none;
        }
        -->
    </STYLE>
{/literal}
<div class="bo_div" id="bo_div">
    <div class="bo_title">{l s='back-order' mod='blockbackorder'}</div>
    {if $message !== null}
        <div class="{if $hasError}error{else}success{/if}">{$message}</div>
    {/if}
    <div class="bo_description">{l s='Input information to back-order' mod='blockbackorder'}</div>
    <form action="{$REQUEST_URI}" method="post">
        <label class="bo_label">{l s='Name' mod='blockbackorder'}</label>
        <input class="text" type="text" value="{$smarty.post.firstname}" name="firstname">
        <br/><br/>
        <label class="bo_label">{l s='Surname' mod='blockbackorder'}</label>
        <input class="text" type="text" value="{$smarty.post.surname}" name="surname">
        <br/><br/>
        <label class="bo_label">{l s='Phone' mod='blockbackorder'}</label>
        <input class="text" type="text" value="{$smarty.post.phone}" name="phone">
        <br/><br/>
        <label class="bo_label">{l s='E-mail' mod='blockbackorder'}</label>
        <input class="text" type="text" value="{$smarty.post.email}" name="email">
        <br/><br/>
        <label class="bo_label">{l s='City' mod='blockbackorder'}</label>
        <input class="text" type="text" value="{$smarty.post.city}" name="city">
        <br/><br/>
        <label class="bo_label">{l s='Comment' mod='blockbackorder'}</label>
        <textarea cols="60" rows="3" name="comment">{$smarty.post.comment}</textarea>
        <br/><br/>
        <center><input type="submit" name="bo_submit" value="{l s='Submit' mod='blockbackorder'}" class="button"/>
        </center>
    </form>
</div>
<!-- /MODULE blockbackorder -->