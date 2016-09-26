{**
* Block Back Order: module for PrestaShop 1.2-1.6
*
* @author      zapalm <zapalm@ya.ru>
* @copyright   (c) 2011-2016, zapalm
* @link        http://prestashop.modulez.ru/en/frontend-features/40-pre-order-form.html Module's homepage
* @license     http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
*}

<!-- MODULE blockbackorder -->
<script type="text/javascript">
// <![CDATA[
    {literal}
        $("document").ready(function () {
            $("#add_to_cart").hide();
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
        }
        div.margin-form {
            display: block;
            float: left;
            clear: both;
            margin-bottom: 5px;
        }
        form.bo-form {

        }
        -->
    </STYLE>
{/literal}
<div class="bo_div" id="bo_div">
    <h3 class="bo_title">{l s='Product pre-order' mod='blockbackorder'}</h3>
    {if $message !== null}
        <div class="{if $hasError}error alert alert-danger{else}success alert alert-success{/if}">{$message}</div>
    {/if}
    <div class="bo_description">{l s='Please fill all fields for the product pre-order.' mod='blockbackorder'}</div>
    <form class="bo-form" action="#bo_div" method="post">
        <div class="margin-form">
            <label class="bo_label">{l s='Name' mod='blockbackorder'}</label>
            <input class="text form-control" type="text" value="{if isset($smarty.post.firstname)}{$smarty.post.firstname}{/if}" name="firstname">
        </div>

        <div class="margin-form">
            <label class="bo_label">{l s='Surname' mod='blockbackorder'}</label>
            <input class="text form-control" type="text" value="{if isset($smarty.post.surname)}{$smarty.post.surname}{/if}" name="surname">
        </div>

        <div class="margin-form">
            <label class="bo_label">{l s='Phone' mod='blockbackorder'}</label>
            <input class="text form-control" type="text" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" name="phone">
        </div>

        <div class="margin-form">
            <label class="bo_label">{l s='E-mail' mod='blockbackorder'}</label>
            <input class="text form-control" type="text" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" name="email">
        </div>

        <div class="margin-form">
            <label class="bo_label">{l s='City' mod='blockbackorder'}</label>
            <input class="text form-control" type="text" value="{if isset($smarty.post.city)}{$smarty.post.city}{/if}" name="city">
        </div>

        <div class="margin-form">
            <label class="bo_label">{l s='Comment' mod='blockbackorder'}</label>
            <textarea class="form-control" cols="60" rows="3" name="comment">{if isset($smarty.post.comment)}{$smarty.post.comment}{/if}</textarea>
        </div>

        <div class="margin-form">
            <input type="submit" name="bo_submit" value="{l s='Submit' mod='blockbackorder'}" class="button"/>
        </div>
    </form>
</div>
<!-- /MODULE blockbackorder -->