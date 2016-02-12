{{-- Share button section --}}
<div class = "event-detail-col-r-widget">

	<div class = "events-detail-title-r-col">Share</div>

	  <div class = "events-detail-info">

	    {{-- Dropdown button that displays share by social--}}
		  <a class="btn btn-block btn-social social-dropdown collapsed" ng-click="socialDropdown(true)" ng-class="{inviteShareDropped: socialDropdown === true}" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
		  	<span class="fa fa-share-square-o"></span>Social<span class="caret"></span>
		  </a>

			{{-- Start of dropdown with buttons in well --}}
		  <div class="collapse social-container well well-sm" id="collapseExample">

		    {{-- Facebook share button --}}
		    <a class="btn btn-block btn-social btn-facebook"
		       href="https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}&title={{ $events['title'] }}"
		       onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
		       target="_blank"
		       title="Share on Facebook">
		       <span class="fa fa-facebook"></span> Facebook
		    </a>

		    {{-- Twitter share button --}}
		    <a class="btn btn-block btn-social btn-twitter"
		       href="https://twitter.com/share?url={{ Request::url() }}&via=TWITTER_HANDLE&text=TEXT"
		       onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
		       target="_blank"
		       title="Share on Twitter">
		       <span class="fa fa-twitter"></span> Twitter
		    </a>

		    {{-- LinkedIn share button --}}
		    <a class="btn btn-block btn-social btn-linkedin"
		       href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ Request::url() }}&amp;title={{ $events['title'] }}&amp;summary={{ $events['description'] }}"
		       target="_blank"
		       title="Share on Linkedin">
		       <span class="fa fa-linkedin"></span> Linkedin
		    </a>

        @if (isset($events['photos'][0]))
  		    {{-- Pinterest share button --}}
  		    <a class="btn btn-block btn-social btn-pinterest"
  		       href="http://pinterest.com/pin/create/button/?url={{ Request::url() }}&media={{$events['photos']['0']['url']['318x190']}}&description={{ $events['description'] }}"
  		       onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');return false;"
  		       target="_blank"
  		       title="Share on Pinterest">
  		       <span class="fa fa-pinterest"></span> Pinterest
  		    </a>
        @endif

		    {{-- Google share button --}}
		    <a class="btn btn-block btn-social btn-google-plus"
		       href="https://plus.google.com/share?url={{ Request::url() }}"
		       onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');return false;"
		       target="_blank"
		       title="Share on Google+">
		       <span class="fa fa-google-plus"></span> Google
		    </a>

        @if (isset($events['photos'][0]))
  		    {{-- Tumblr share button --}}
  		    <a class="btn btn-block btn-social btn-tumblr"
  		       href="http://www.tumblr.com/share/photo?source={{$events['photos']['0']['url']['318x190']}}&caption={{ $events['description'] }}&click_thru={{ Request::url() }}"
  		       onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');return false;"
  		       target="_blank"
  		       title="Share on Tumblr">
  		       <span class="fa fa-tumblr"></span> Tumblr
  		    </a>
        @endif

		    {{-- Reddit share button --}}
		    <a class="btn btn-block btn-social btn-reddit"
		       href="http://www.reddit.com/submit?url={{ Request::url() }}&title={{ $events['title'] }}"
		       target="_blank"
		       title="Share on Reddit">
		       <span class="fa fa-reddit"></span> Reddit
		    </a>

		  </div>

		</div>

</div>
