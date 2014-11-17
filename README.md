This will help people who are trying to use nodus and trying to use php website to call NODUS E-Pay website.

It will generate autologin URL for user.

Import the class then

$nodus_page = new nodus_clinet;
echo $nodus_page_link =  $nodus_page->client_url('account number');

Downside for using this method is if url is exposed then everbody can get in.
