<x-mail::message>

    Hello {{ $username }},<br>
    We're excited to let you know that you've got a new follower <a href="{{config('app.front_profile_url') . '/'.$followerUserName}}"><b>{{ $followerUserName  }}</b></a> on Screel!
    Your content and engagement have caught the eye of another member of our community, and they've decided to follow you to stay updated on your latest Screels and activities.

    We're thrilled that Screel is helping you connect with like-minded individuals and we hope that you'll continue to share your passions and interests with our community.

    Don't forget that you can also follow other Screelers to stay updated on their latest Screels and activities. Engaging with the community is a great way to discover new content and make new connections.

    Keep Screeling!🚀🔥🔥

<x-mail::button url="{{config('app.front_profile_url') . '/'.$followerUserName}}">
    View profile
</x-mail::button>

Best,<br>
The Screel Team
    <img src="{{asset('/assets/img/screel_logo.png')}}" alt="Screel logo">
</x-mail::message>


