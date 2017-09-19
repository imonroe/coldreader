<form class="form-inline my-2 my-lg-0" id="basic_search_form" method="POST" action="/search/results">
	{{ csrf_field() }}
	<div class="form-group" class="ui-front">
		<input type="text" class="form-control mr-sm-2 subject-autocomplete" id="search_form_query" name="search_form_query" placeholder="Search">
	</div>
	<button type="submit" class="btn btn-default">Go</button>
</form>