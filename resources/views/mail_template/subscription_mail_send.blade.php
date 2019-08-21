<html>
<head>
    <title>newsletter</title>
</head>
<body>
<p>Hi</p>
<p>Check out these new teaching jobs posted on ESL Jobs Lounge! You are receiving this job alert because you are an active user of ESL Jobs Lounge. If you wish to change the frequency of emails or country preferences for your job alerts</p>
<hr>
@foreach($favourite_articles as $favourite_article)
<h3><a href="{{ route('articles.show',$favourite_article->slug) }}">{{ $favourite_article->title }}</a></h3>
<h5>{{ $favourite_article->meta_description }}</h5>
@endforeach
</body>
</html>