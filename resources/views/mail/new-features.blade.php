<x-mail::message>

Dear <a href="{{config('app.front_profile_url') . '/'.$username}}">{{ '@'.$username  }}</a>,

<p>
    We are excited to announce that we have added new features to Screel to enhance your experience and help you connect with other like-minded individuals.
    Some of the new features include:
</p>
<div>
<b>* Post screel with tags</b><br>
<b>* Follow feature</b>
<ul>
    <li>Follow screeler.</li>
    <li>Unfollow screeler.</li>
    <li>List your followers.</li>
    <li>List your following.</li>
</ul>
<b>* Email notification on getting new follower. (Screeler will be able to turn it off on next features)</b><br>
</div>
    <br>
<p>
    We have also made some improvements to the app's performance and design to make it more user-friendly.
    To access these new features, simply update your Screel app from the browser.
    We hope that you will enjoy these new features and continue to be a part of our community.
    As always, we value your feedback, so please let us know what you think of the new updates in our <a href="https://discord.gg/rSb2eUZYsk">Discord channel</a>.
</p>

Thank you for your support,<br>
The Screel Team 🚀🔥🔥

<x-mail::button url="{{config('app.front_url')}}">
    View updates
</x-mail::button>

Best,<br>
The Screel Team <br>
<div class="flex flex-row justify-between items-center">
    <a href="{{config('app.front_url')}}"><img src="{{asset('/assets/img/screel_logo.png')}}" alt="Screel logo" width="30" height="30"></a>
    <a href="https://discord.gg/rSb2eUZYsk"><img src="{{asset('/assets/img/discord.png')}}" alt="Discord logo" width="30" height="30"></a>
</div>
</x-mail::message>


