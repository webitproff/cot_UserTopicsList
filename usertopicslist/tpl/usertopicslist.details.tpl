<!-- BEGIN: USERTOPICSLIST -->
<h4 class="uk-link-text">{PHP.L.usertopicslist_title}</h4>
<!-- BEGIN: YES -->
{UPF_AJAX_BEGIN}
<ul class="uk-list uk-list-divider">
	<!-- BEGIN: TOPIC -->
	    <li>
	{UPF_DATE}: | <a href="{UPF_TOPIC_POST_URL}"><span class="uk-text-primary">{UPF_TOPIC_TITLE}</span></a> | {UPF_TOPIC} <br />
	{UPF_TOPIC_TEXT}<br />
	</li>
	<!-- END: TOPIC -->
</ul>
    <div class="uk-margin">
      <ul class="uk-pagination uk-flex-center" uk-margin>{UPF_PAGENAV_PREV}&nbsp;{UPF_PAGENAV}&nbsp;{UPF_PAGENAV_NEXT}</ul>
    </div>
{PHP.L.Total} : {UPF_TOTALITEMS}, {PHP.L.Onpage} : {UPF_COUNT_ON_PAGE}
{UPF_AJAX_END}
<!-- END: YES -->

<!-- BEGIN: NONE -->
    <div class="uk-card uk-card-small uk-card-body uk-background-pink border-pink uk-border-rounded uk-margin-medium-bottom">
      <p class="">{PHP.L.None}</p>
    </div>

<!-- END: NONE -->

<!-- END: USERTOPICSLIST -->