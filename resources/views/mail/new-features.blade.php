<x-mail::message>

    Dear Screelers,

    We are excited to announce that we have added new features to Screel to enhance your experience and help you connect with other like-minded individuals.

    Some of the new features include:
<b>* {{$feature->title}}</b>
<ul>
    @foreach($feature->content as $ctt)
        <li>{{$ctt['title']}} : {{$ctt['description']}}</li>
    @endforeach
</ul>

    We have also made some improvements to the app's performance and design to make it more user-friendly.

    To access these new features, simply update your Screel app from the browser.

    We hope that you will enjoy these new features and continue to be a part of our community.
    As always, we value your feedback, so please let us know what you think of the new updates.

    Thank you for your support,
    The Screel Team 🚀🔥🔥

<x-mail::button url="{{config('app.front_url')}}">
    View updates
</x-mail::button>

Best,<br>
The Screel Team <br>
    <div class="flex flex-row justify-between">
        <a href="{{config('app.front_url')}}"><img src="{{asset('/assets/img/screel_logo.png')}}" alt="Screel logo" width="30" height="30"></a>
        <a href="https://discord.gg/7mqC4qc7bt"><img src="{{asset('/assets/img/discord.png')}}" alt="Discord logo" width="30" height="30"></a>
    </div>
</x-mail::message>


