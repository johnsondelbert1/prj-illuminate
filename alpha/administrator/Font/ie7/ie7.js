/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referring to this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icons\'">' + entity + '</span>' + html;
	}
	var icons = {
		'icon-home': '&#xe60a;',
		'icon-home2': '&#xe60b;',
		'icon-home3': '&#xe60c;',
		'icon-office': '&#xe60d;',
		'icon-newspaper': '&#xe60e;',
		'icon-pencil': '&#xe60f;',
		'icon-pencil2': '&#xe610;',
		'icon-quill': '&#xe611;',
		'icon-pen': '&#xe612;',
		'icon-blog': '&#xe613;',
		'icon-droplet': '&#xe614;',
		'icon-paint-format': '&#xe615;',
		'icon-image': '&#xe616;',
		'icon-image2': '&#xe617;',
		'icon-images': '&#xe618;',
		'icon-camera': '&#xe619;',
		'icon-music': '&#xe61a;',
		'icon-headphones': '&#xe61b;',
		'icon-play': '&#xe61c;',
		'icon-film': '&#xe61d;',
		'icon-camera2': '&#xe61e;',
		'icon-dice': '&#xe61f;',
		'icon-pacman': '&#xe620;',
		'icon-spades': '&#xe621;',
		'icon-clubs': '&#xe622;',
		'icon-diamonds': '&#xe623;',
		'icon-pawn': '&#xe624;',
		'icon-bullhorn': '&#xe625;',
		'icon-connection': '&#xe626;',
		'icon-podcast': '&#xe627;',
		'icon-feed': '&#xe628;',
		'icon-book': '&#xe629;',
		'icon-books': '&#xe62a;',
		'icon-library': '&#xe62b;',
		'icon-file': '&#xe62c;',
		'icon-profile': '&#xe62d;',
		'icon-file2': '&#xe62e;',
		'icon-file3': '&#xe62f;',
		'icon-file4': '&#xe630;',
		'icon-copy': '&#xe631;',
		'icon-copy2': '&#xe632;',
		'icon-copy3': '&#xe633;',
		'icon-paste': '&#xe634;',
		'icon-paste2': '&#xe635;',
		'icon-paste3': '&#xe636;',
		'icon-stack': '&#xe637;',
		'icon-folder': '&#xe638;',
		'icon-folder-open': '&#xe639;',
		'icon-tag': '&#xe63a;',
		'icon-tags': '&#xe63b;',
		'icon-barcode': '&#xe63c;',
		'icon-qrcode': '&#xe63d;',
		'icon-ticket': '&#xe63e;',
		'icon-cart': '&#xe63f;',
		'icon-cart2': '&#xe640;',
		'icon-cart3': '&#xe641;',
		'icon-coin': '&#xe642;',
		'icon-credit': '&#xe643;',
		'icon-calculate': '&#xe644;',
		'icon-support': '&#xe645;',
		'icon-phone': '&#xe646;',
		'icon-phone-hang-up': '&#xe647;',
		'icon-address-book': '&#xe648;',
		'icon-notebook': '&#xe649;',
		'icon-envelope': '&#xe64a;',
		'icon-pushpin': '&#xe64b;',
		'icon-location': '&#xe64c;',
		'icon-location2': '&#xe64d;',
		'icon-compass': '&#xe64e;',
		'icon-map': '&#xe64f;',
		'icon-map2': '&#xe650;',
		'icon-history': '&#xe651;',
		'icon-clock': '&#xe652;',
		'icon-clock2': '&#xe653;',
		'icon-alarm': '&#xe654;',
		'icon-alarm2': '&#xe655;',
		'icon-bell': '&#xe656;',
		'icon-stopwatch': '&#xe657;',
		'icon-calendar': '&#xe658;',
		'icon-calendar2': '&#xe659;',
		'icon-print': '&#xe65a;',
		'icon-keyboard': '&#xe65b;',
		'icon-screen': '&#xe65c;',
		'icon-laptop': '&#xe65d;',
		'icon-mobile': '&#xe65e;',
		'icon-mobile2': '&#xe65f;',
		'icon-tablet': '&#xe660;',
		'icon-tv': '&#xe661;',
		'icon-cabinet': '&#xe662;',
		'icon-drawer': '&#xe663;',
		'icon-drawer2': '&#xe664;',
		'icon-drawer3': '&#xe665;',
		'icon-box-add': '&#xe666;',
		'icon-box-remove': '&#xe667;',
		'icon-download': '&#xe668;',
		'icon-upload': '&#xe669;',
		'icon-disk': '&#xe66a;',
		'icon-storage': '&#xe66b;',
		'icon-undo': '&#xe66c;',
		'icon-redo': '&#xe66d;',
		'icon-flip': '&#xe66e;',
		'icon-flip2': '&#xe66f;',
		'icon-undo2': '&#xe670;',
		'icon-redo2': '&#xe671;',
		'icon-forward': '&#xe672;',
		'icon-reply': '&#xe673;',
		'icon-bubble': '&#xe674;',
		'icon-bubbles': '&#xe675;',
		'icon-bubbles2': '&#xe676;',
		'icon-bubble2': '&#xe677;',
		'icon-bubbles3': '&#xe678;',
		'icon-bubbles4': '&#xe679;',
		'icon-user': '&#xe67a;',
		'icon-users': '&#xe67b;',
		'icon-user2': '&#xe67c;',
		'icon-users2': '&#xe67d;',
		'icon-user3': '&#xe67e;',
		'icon-user4': '&#xe67f;',
		'icon-quotes-left': '&#xe680;',
		'icon-busy': '&#xe681;',
		'icon-spinner': '&#xe682;',
		'icon-spinner2': '&#xe683;',
		'icon-spinner3': '&#xe684;',
		'icon-spinner4': '&#xe685;',
		'icon-spinner5': '&#xe686;',
		'icon-spinner6': '&#xe687;',
		'icon-binoculars': '&#xe688;',
		'icon-search': '&#xe689;',
		'icon-zoomin': '&#xe68a;',
		'icon-zoomout': '&#xe68b;',
		'icon-expand': '&#xe68c;',
		'icon-contract': '&#xe68d;',
		'icon-expand2': '&#xe68e;',
		'icon-contract2': '&#xe68f;',
		'icon-key': '&#xe690;',
		'icon-key2': '&#xe691;',
		'icon-lock': '&#xe692;',
		'icon-lock2': '&#xe693;',
		'icon-unlocked': '&#xe694;',
		'icon-wrench': '&#xe695;',
		'icon-settings': '&#xe696;',
		'icon-equalizer': '&#xe697;',
		'icon-cog': '&#xe698;',
		'icon-cogs': '&#xe699;',
		'icon-cog2': '&#xe69a;',
		'icon-hammer': '&#xe69b;',
		'icon-wand': '&#xe69c;',
		'icon-aid': '&#xe69d;',
		'icon-bug': '&#xe69e;',
		'icon-pie': '&#xe69f;',
		'icon-stats': '&#xe6a0;',
		'icon-bars': '&#xe6a1;',
		'icon-bars2': '&#xe6a2;',
		'icon-gift': '&#xe6a3;',
		'icon-trophy': '&#xe6a4;',
		'icon-glass': '&#xe6a5;',
		'icon-mug': '&#xe6a6;',
		'icon-food': '&#xe6a7;',
		'icon-leaf': '&#xe6a8;',
		'icon-rocket': '&#xe6a9;',
		'icon-meter': '&#xe6aa;',
		'icon-meter2': '&#xe6ab;',
		'icon-dashboard': '&#xe6ac;',
		'icon-hammer2': '&#xe6ad;',
		'icon-fire': '&#xe6ae;',
		'icon-lab': '&#xe6af;',
		'icon-magnet': '&#xe6b0;',
		'icon-remove': '&#xe6b1;',
		'icon-remove2': '&#xe6b2;',
		'icon-briefcase': '&#xe6b3;',
		'icon-airplane': '&#xe6b4;',
		'icon-truck': '&#xe6b5;',
		'icon-road': '&#xe6b6;',
		'icon-accessibility': '&#xe6b7;',
		'icon-target': '&#xe6b8;',
		'icon-shield': '&#xe6b9;',
		'icon-lightning': '&#xe6ba;',
		'icon-switch': '&#xe6bb;',
		'icon-powercord': '&#xe6bc;',
		'icon-signup': '&#xe6bd;',
		'icon-list': '&#xe6be;',
		'icon-list2': '&#xe6bf;',
		'icon-numbered-list': '&#xe6c0;',
		'icon-menu': '&#xe6c1;',
		'icon-menu2': '&#xe6c2;',
		'icon-tree': '&#xe6c3;',
		'icon-cloud': '&#xe6c4;',
		'icon-cloud-download': '&#xe6c5;',
		'icon-cloud-upload': '&#xe6c6;',
		'icon-download2': '&#xe6c7;',
		'icon-upload2': '&#xe6c8;',
		'icon-download3': '&#xe6c9;',
		'icon-upload3': '&#xe6ca;',
		'icon-globe': '&#xe6cb;',
		'icon-earth': '&#xe6cc;',
		'icon-link': '&#xe6cd;',
		'icon-flag': '&#xe6ce;',
		'icon-attachment': '&#xe6cf;',
		'icon-eye': '&#xe6d0;',
		'icon-eye-blocked': '&#xe6d1;',
		'icon-eye2': '&#xe6d2;',
		'icon-bookmark': '&#xe6d3;',
		'icon-bookmarks': '&#xe6d4;',
		'icon-brightness-medium': '&#xe6d5;',
		'icon-brightness-contrast': '&#xe6d6;',
		'icon-contrast': '&#xe6d7;',
		'icon-star': '&#xe6d8;',
		'icon-star2': '&#xe6d9;',
		'icon-star3': '&#xe6da;',
		'icon-heart': '&#xe6db;',
		'icon-heart2': '&#xe6dc;',
		'icon-heart-broken': '&#xe6dd;',
		'icon-thumbs-up': '&#xe6de;',
		'icon-thumbs-up2': '&#xe6df;',
		'icon-happy': '&#xe6e0;',
		'icon-happy2': '&#xe6e1;',
		'icon-smiley': '&#xe6e2;',
		'icon-smiley2': '&#xe6e3;',
		'icon-tongue': '&#xe6e4;',
		'icon-tongue2': '&#xe6e5;',
		'icon-sad': '&#xe6e6;',
		'icon-sad2': '&#xe6e7;',
		'icon-wink': '&#xe6e8;',
		'icon-wink2': '&#xe6e9;',
		'icon-grin': '&#xe6ea;',
		'icon-grin2': '&#xe6eb;',
		'icon-cool': '&#xe6ec;',
		'icon-cool2': '&#xe6ed;',
		'icon-angry': '&#xe6ee;',
		'icon-angry2': '&#xe6ef;',
		'icon-evil': '&#xe6f0;',
		'icon-evil2': '&#xe6f1;',
		'icon-shocked': '&#xe6f2;',
		'icon-shocked2': '&#xe6f3;',
		'icon-confused': '&#xe6f4;',
		'icon-confused2': '&#xe6f5;',
		'icon-neutral': '&#xe6f6;',
		'icon-neutral2': '&#xe6f7;',
		'icon-wondering': '&#xe6f8;',
		'icon-wondering2': '&#xe6f9;',
		'icon-point-up': '&#xe6fa;',
		'icon-point-right': '&#xe6fb;',
		'icon-point-down': '&#xe6fc;',
		'icon-point-left': '&#xe6fd;',
		'icon-warning': '&#xe6fe;',
		'icon-notification': '&#xe6ff;',
		'icon-question': '&#xe700;',
		'icon-info': '&#xe701;',
		'icon-info2': '&#xe702;',
		'icon-blocked': '&#xe703;',
		'icon-cancel-circle': '&#xe704;',
		'icon-checkmark-circle': '&#xe705;',
		'icon-spam': '&#xe706;',
		'icon-close': '&#xe707;',
		'icon-checkmark': '&#xe708;',
		'icon-checkmark2': '&#xe709;',
		'icon-spell-check': '&#xe70a;',
		'icon-minus': '&#xe70b;',
		'icon-plus': '&#xe70c;',
		'icon-enter': '&#xe70d;',
		'icon-exit': '&#xe70e;',
		'icon-play2': '&#xe601;',
		'icon-pause': '&#xe602;',
		'icon-stop': '&#xe603;',
		'icon-backward': '&#xe604;',
		'icon-forward2': '&#xe605;',
		'icon-play22': '&#xe606;',
		'icon-pause2': '&#xe70f;',
		'icon-stop2': '&#xe710;',
		'icon-backward2': '&#xe711;',
		'icon-forward3': '&#xe712;',
		'icon-first': '&#xe713;',
		'icon-last': '&#xe714;',
		'icon-previous': '&#xe607;',
		'icon-next': '&#xe608;',
		'icon-eject': '&#xe609;',
		'icon-volume-high': '&#xe715;',
		'icon-volume-medium': '&#xe716;',
		'icon-volume-low': '&#xe717;',
		'icon-volume-mute': '&#xe718;',
		'icon-volume-mute2': '&#xe719;',
		'icon-volume-increase': '&#xe71a;',
		'icon-volume-decrease': '&#xe71b;',
		'icon-loop': '&#xe71c;',
		'icon-loop2': '&#xe71d;',
		'icon-loop3': '&#xe71e;',
		'icon-shuffle': '&#xe71f;',
		'icon-arrow-up-left': '&#xe720;',
		'icon-arrow-up': '&#xe721;',
		'icon-arrow-up-right': '&#xe722;',
		'icon-arrow-right': '&#xe723;',
		'icon-arrow-down-right': '&#xe724;',
		'icon-arrow-down': '&#xe725;',
		'icon-arrow-down-left': '&#xe726;',
		'icon-arrow-left': '&#xe727;',
		'icon-arrow-up-left2': '&#xe728;',
		'icon-arrow-up2': '&#xe729;',
		'icon-arrow-up-right2': '&#xe72a;',
		'icon-arrow-right2': '&#xe72b;',
		'icon-arrow-down-right2': '&#xe72c;',
		'icon-arrow-down2': '&#xe72d;',
		'icon-arrow-down-left2': '&#xe72e;',
		'icon-arrow-left2': '&#xe72f;',
		'icon-arrow-up-left3': '&#xe730;',
		'icon-arrow-up3': '&#xe731;',
		'icon-arrow-up-right3': '&#xe732;',
		'icon-arrow-right3': '&#xe733;',
		'icon-arrow-down-right3': '&#xe734;',
		'icon-arrow-down3': '&#xe735;',
		'icon-arrow-down-left3': '&#xe736;',
		'icon-arrow-left3': '&#xe737;',
		'icon-tab': '&#xe738;',
		'icon-checkbox-checked': '&#xe739;',
		'icon-checkbox-unchecked': '&#xe73a;',
		'icon-checkbox-partial': '&#xe73b;',
		'icon-radio-checked': '&#xe73c;',
		'icon-radio-unchecked': '&#xe73d;',
		'icon-crop': '&#xe73e;',
		'icon-scissors': '&#xe73f;',
		'icon-filter': '&#xe740;',
		'icon-filter2': '&#xe741;',
		'icon-font': '&#xe742;',
		'icon-text-height': '&#xe743;',
		'icon-text-width': '&#xe744;',
		'icon-bold': '&#xe745;',
		'icon-underline': '&#xe746;',
		'icon-italic': '&#xe747;',
		'icon-strikethrough': '&#xe748;',
		'icon-omega': '&#xe749;',
		'icon-sigma': '&#xe74a;',
		'icon-table': '&#xe74b;',
		'icon-table2': '&#xe74c;',
		'icon-insert-template': '&#xe74d;',
		'icon-pilcrow': '&#xe74e;',
		'icon-lefttoright': '&#xe74f;',
		'icon-righttoleft': '&#xe750;',
		'icon-paragraph-left': '&#xe751;',
		'icon-paragraph-center': '&#xe752;',
		'icon-paragraph-right': '&#xe753;',
		'icon-paragraph-justify': '&#xe754;',
		'icon-paragraph-left2': '&#xe755;',
		'icon-paragraph-center2': '&#xe756;',
		'icon-paragraph-right2': '&#xe757;',
		'icon-paragraph-justify2': '&#xe758;',
		'icon-indent-increase': '&#xe759;',
		'icon-indent-decrease': '&#xe75a;',
		'icon-newtab': '&#xe75b;',
		'icon-embed': '&#xe75c;',
		'icon-code': '&#xe75d;',
		'icon-console': '&#xe75e;',
		'icon-share': '&#xe75f;',
		'icon-mail': '&#xe760;',
		'icon-mail2': '&#xe761;',
		'icon-mail3': '&#xe762;',
		'icon-mail4': '&#xe763;',
		'icon-google': '&#xe764;',
		'icon-googleplus': '&#xe765;',
		'icon-googleplus2': '&#xe766;',
		'icon-googleplus3': '&#xe767;',
		'icon-googleplus4': '&#xe768;',
		'icon-google-drive': '&#xe769;',
		'icon-facebook': '&#xe76a;',
		'icon-facebook2': '&#xe76b;',
		'icon-facebook3': '&#xe76c;',
		'icon-instagram': '&#xe76d;',
		'icon-twitter': '&#xe76e;',
		'icon-twitter2': '&#xe76f;',
		'icon-twitter3': '&#xe770;',
		'icon-feed2': '&#xe771;',
		'icon-feed3': '&#xe772;',
		'icon-feed4': '&#xe773;',
		'icon-youtube': '&#xe774;',
		'icon-youtube2': '&#xe775;',
		'icon-vimeo': '&#xe776;',
		'icon-vimeo2': '&#xe777;',
		'icon-vimeo3': '&#xe778;',
		'icon-lanyrd': '&#xe779;',
		'icon-flickr': '&#xe77a;',
		'icon-flickr2': '&#xe77b;',
		'icon-flickr3': '&#xe77c;',
		'icon-flickr4': '&#xe77d;',
		'icon-picassa': '&#xe77e;',
		'icon-picassa2': '&#xe77f;',
		'icon-dribbble': '&#xe780;',
		'icon-dribbble2': '&#xe781;',
		'icon-dribbble3': '&#xe782;',
		'icon-forrst': '&#xe783;',
		'icon-forrst2': '&#xe784;',
		'icon-deviantart': '&#xe785;',
		'icon-deviantart2': '&#xe786;',
		'icon-steam': '&#xe787;',
		'icon-steam2': '&#xe788;',
		'icon-github': '&#xe789;',
		'icon-github2': '&#xe78a;',
		'icon-github3': '&#xe78b;',
		'icon-github4': '&#xe78c;',
		'icon-github5': '&#xe78d;',
		'icon-wordpress': '&#xe78e;',
		'icon-wordpress2': '&#xe78f;',
		'icon-joomla': '&#xe790;',
		'icon-blogger': '&#xe791;',
		'icon-blogger2': '&#xe792;',
		'icon-tumblr': '&#xe793;',
		'icon-tumblr2': '&#xe794;',
		'icon-yahoo': '&#xe795;',
		'icon-tux': '&#xe796;',
		'icon-apple': '&#xe797;',
		'icon-finder': '&#xe798;',
		'icon-android': '&#xe799;',
		'icon-windows': '&#xe79a;',
		'icon-windows8': '&#xe79b;',
		'icon-soundcloud': '&#xe79c;',
		'icon-soundcloud2': '&#xe79d;',
		'icon-skype': '&#xe79e;',
		'icon-reddit': '&#xe79f;',
		'icon-linkedin': '&#xe7a0;',
		'icon-lastfm': '&#xe7a1;',
		'icon-lastfm2': '&#xe7a2;',
		'icon-delicious': '&#xe7a3;',
		'icon-stumbleupon': '&#xe7a4;',
		'icon-stumbleupon2': '&#xe7a5;',
		'icon-stackoverflow': '&#xe7a6;',
		'icon-pinterest': '&#xe7a7;',
		'icon-pinterest2': '&#xe7a8;',
		'icon-xing': '&#xe7a9;',
		'icon-xing2': '&#xe7aa;',
		'icon-flattr': '&#xe7ab;',
		'icon-foursquare': '&#xe7ac;',
		'icon-foursquare2': '&#xe7ad;',
		'icon-paypal': '&#xe7ae;',
		'icon-paypal2': '&#xe7af;',
		'icon-paypal3': '&#xe7b0;',
		'icon-yelp': '&#xe7b1;',
		'icon-libreoffice': '&#xe7b2;',
		'icon-file-pdf': '&#xe7b3;',
		'icon-file-openoffice': '&#xe7b4;',
		'icon-file-word': '&#xe7b5;',
		'icon-file-excel': '&#xe7b6;',
		'icon-file-zip': '&#xe7b7;',
		'icon-file-powerpoint': '&#xe7b8;',
		'icon-file-xml': '&#xe7b9;',
		'icon-file-css': '&#xe7ba;',
		'icon-html5': '&#xe7bb;',
		'icon-html52': '&#xe7bc;',
		'icon-css3': '&#xe7bd;',
		'icon-chrome': '&#xe7be;',
		'icon-firefox': '&#xe7bf;',
		'icon-IE': '&#xe7c0;',
		'icon-opera': '&#xe7c1;',
		'icon-safari': '&#xe7c2;',
		'icon-IcoMoon': '&#xe7c3;',
		'icon-phone2': '&#xe7c4;',
		'icon-mobile3': '&#xe7c5;',
		'icon-mouse': '&#xe7c6;',
		'icon-directions': '&#xe7c7;',
		'icon-mail5': '&#xe7c8;',
		'icon-paperplane': '&#xe7c9;',
		'icon-pencil3': '&#xe7ca;',
		'icon-feather': '&#xe7cb;',
		'icon-paperclip': '&#xe7cc;',
		'icon-drawer4': '&#xe7cd;',
		'icon-reply2': '&#xe7ce;',
		'icon-reply-all': '&#xe7cf;',
		'icon-forward4': '&#xe7d0;',
		'icon-user5': '&#xe7d1;',
		'icon-users3': '&#xe7d2;',
		'icon-user-add': '&#xe7d3;',
		'icon-vcard': '&#xe7d4;',
		'icon-export': '&#xe7d5;',
		'icon-location3': '&#xe7d6;',
		'icon-map3': '&#xe7d7;',
		'icon-compass2': '&#xe7d8;',
		'icon-location4': '&#xe7d9;',
		'icon-target2': '&#xe7da;',
		'icon-share2': '&#xe7db;',
		'icon-sharable': '&#xe7dc;',
		'icon-heart3': '&#xe7dd;',
		'icon-heart4': '&#xe7de;',
		'icon-star4': '&#xe7df;',
		'icon-star5': '&#xe7e0;',
		'icon-thumbsup': '&#xe7e1;',
		'icon-thumbsdown': '&#xe7e2;',
		'icon-chat': '&#xe7e3;',
		'icon-comment': '&#xe7e4;',
		'icon-quote': '&#xe7e5;',
		'icon-house': '&#xe7e6;',
		'icon-popup': '&#xe7e7;',
		'icon-search2': '&#xe7e8;',
		'icon-flashlight': '&#xe7e9;',
		'icon-printer': '&#xe7ea;',
		'icon-bell2': '&#xe7eb;',
		'icon-link2': '&#xe7ec;',
		'icon-flag2': '&#xe7ed;',
		'icon-cog3': '&#xe7ee;',
		'icon-tools': '&#xe7ef;',
		'icon-trophy2': '&#xe7f0;',
		'icon-tag2': '&#xe7f1;',
		'icon-camera3': '&#xe7f2;',
		'icon-megaphone': '&#xe7f3;',
		'icon-moon': '&#xe7f4;',
		'icon-palette': '&#xe7f5;',
		'icon-leaf2': '&#xe7f6;',
		'icon-music2': '&#xe7f7;',
		'icon-music3': '&#xe7f8;',
		'icon-new': '&#xe7f9;',
		'icon-graduation': '&#xe7fa;',
		'icon-book2': '&#xe7fb;',
		'icon-newspaper2': '&#xe7fc;',
		'icon-bag': '&#xe7fd;',
		'icon-airplane2': '&#xe7fe;',
		'icon-lifebuoy': '&#xe7ff;',
		'icon-eye3': '&#xe800;',
		'icon-clock3': '&#xe801;',
		'icon-microphone': '&#xe802;',
		'icon-calendar3': '&#xe803;',
		'icon-bolt': '&#xe804;',
		'icon-thunder': '&#xe805;',
		'icon-droplet2': '&#xe806;',
		'icon-cd': '&#xe807;',
		'icon-briefcase2': '&#xe808;',
		'icon-air': '&#xe809;',
		'icon-hourglass': '&#xe80a;',
		'icon-gauge': '&#xe80b;',
		'icon-language': '&#xe80c;',
		'icon-network': '&#xe80d;',
		'icon-key3': '&#xe80e;',
		'icon-battery': '&#xe80f;',
		'icon-bucket': '&#xe810;',
		'icon-magnet2': '&#xe811;',
		'icon-drive': '&#xe812;',
		'icon-cup': '&#xe813;',
		'icon-rocket2': '&#xe814;',
		'icon-brush': '&#xe815;',
		'icon-suitcase': '&#xe816;',
		'icon-cone': '&#xe817;',
		'icon-earth2': '&#xe818;',
		'icon-keyboard2': '&#xe819;',
		'icon-browser': '&#xe81a;',
		'icon-publish': '&#xe81b;',
		'icon-progress-3': '&#xe81c;',
		'icon-progress-2': '&#xe81d;',
		'icon-brogress-1': '&#xe81e;',
		'icon-progress-0': '&#xe81f;',
		'icon-sun': '&#xe820;',
		'icon-sun2': '&#xe821;',
		'icon-adjust': '&#xe822;',
		'icon-code2': '&#xe823;',
		'icon-screen2': '&#xe824;',
		'icon-infinity': '&#xe825;',
		'icon-light-bulb': '&#xe826;',
		'icon-creditcard': '&#xe827;',
		'icon-database': '&#xe828;',
		'icon-voicemail': '&#xe829;',
		'icon-clipboard': '&#xe82a;',
		'icon-cart4': '&#xe82b;',
		'icon-box': '&#xe82c;',
		'icon-ticket2': '&#xe82d;',
		'icon-rss': '&#xe82e;',
		'icon-signal': '&#xe82f;',
		'icon-thermometer': '&#xe830;',
		'icon-droplets': '&#xe831;',
		'icon-uniE832': '&#xe832;',
		'icon-statistics': '&#xe833;',
		'icon-pie2': '&#xe834;',
		'icon-bars3': '&#xe835;',
		'icon-graph': '&#xe836;',
		'icon-lock3': '&#xe837;',
		'icon-lock-open': '&#xe838;',
		'icon-logout': '&#xe839;',
		'icon-login': '&#xe83a;',
		'icon-checkmark3': '&#xe83b;',
		'icon-cross': '&#xe83c;',
		'icon-minus2': '&#xe83d;',
		'icon-plus2': '&#xe83e;',
		'icon-cross2': '&#xe83f;',
		'icon-minus3': '&#xe840;',
		'icon-plus3': '&#xe841;',
		'icon-cross3': '&#xe842;',
		'icon-minus4': '&#xe843;',
		'icon-plus4': '&#xe844;',
		'icon-erase': '&#xe845;',
		'icon-blocked2': '&#xe846;',
		'icon-info3': '&#xe847;',
		'icon-info4': '&#xe848;',
		'icon-question2': '&#xe849;',
		'icon-help': '&#xe84a;',
		'icon-warning2': '&#xe84b;',
		'icon-cycle': '&#xe84c;',
		'icon-cw': '&#xe84d;',
		'icon-ccw': '&#xe84e;',
		'icon-shuffle2': '&#xe84f;',
		'icon-arrow': '&#xe850;',
		'icon-arrow2': '&#xe851;',
		'icon-retweet': '&#xe852;',
		'icon-loop4': '&#xe853;',
		'icon-history2': '&#xe854;',
		'icon-back': '&#xe855;',
		'icon-switch2': '&#xe856;',
		'icon-list3': '&#xe857;',
		'icon-add-to-list': '&#xe858;',
		'icon-layout': '&#xe859;',
		'icon-list4': '&#xe85a;',
		'icon-text': '&#xe85b;',
		'icon-text2': '&#xe85c;',
		'icon-document': '&#xe85d;',
		'icon-docs': '&#xe85e;',
		'icon-landscape': '&#xe85f;',
		'icon-pictures': '&#xe860;',
		'icon-video': '&#xe861;',
		'icon-music4': '&#xe862;',
		'icon-folder2': '&#xe863;',
		'icon-archive': '&#xe864;',
		'icon-trash': '&#xe865;',
		'icon-upload4': '&#xe866;',
		'icon-download4': '&#xe867;',
		'icon-disk2': '&#xe868;',
		'icon-install': '&#xe869;',
		'icon-cloud2': '&#xe86a;',
		'icon-upload5': '&#xe86b;',
		'icon-bookmark2': '&#xe86c;',
		'icon-bookmarks2': '&#xe86d;',
		'icon-book3': '&#xe86e;',
		'icon-play3': '&#xe86f;',
		'icon-pause3': '&#xe870;',
		'icon-record': '&#xe871;',
		'icon-stop3': '&#xe872;',
		'icon-next2': '&#xe873;',
		'icon-previous2': '&#xe874;',
		'icon-first2': '&#xe875;',
		'icon-last2': '&#xe876;',
		'icon-resize-enlarge': '&#xe877;',
		'icon-resize-shrink': '&#xe878;',
		'icon-volume': '&#xe879;',
		'icon-sound': '&#xe87a;',
		'icon-mute': '&#xe87b;',
		'icon-flow-cascade': '&#xe87c;',
		'icon-flow-branch': '&#xe87d;',
		'icon-flow-tree': '&#xe87e;',
		'icon-flow-line': '&#xe87f;',
		'icon-flow-parallel': '&#xe880;',
		'icon-arrow-left4': '&#xe881;',
		'icon-arrow-down4': '&#xe882;',
		'icon-arrow-up-upload': '&#xe883;',
		'icon-arrow-right4': '&#xe884;',
		'icon-arrow-left5': '&#xe885;',
		'icon-arrow-down5': '&#xe886;',
		'icon-arrow-up4': '&#xe887;',
		'icon-arrow-right5': '&#xe888;',
		'icon-arrow-left6': '&#xe889;',
		'icon-arrow-down6': '&#xe88a;',
		'icon-arrow-up5': '&#xe88b;',
		'icon-arrow-right6': '&#xe88c;',
		'icon-arrow-left7': '&#xe88d;',
		'icon-arrow-down7': '&#xe88e;',
		'icon-arrow-up6': '&#xe88f;',
		'icon-arrow-right7': '&#xe890;',
		'icon-arrow-left8': '&#xe891;',
		'icon-arrow-down8': '&#xe892;',
		'icon-arrow-up7': '&#xe893;',
		'icon-arrow-right8': '&#xe894;',
		'icon-arrow-left9': '&#xe895;',
		'icon-arrow-down9': '&#xe896;',
		'icon-arrow-up8': '&#xe897;',
		'icon-arrow-right9': '&#xe898;',
		'icon-arrow-left10': '&#xe899;',
		'icon-arrow-down10': '&#xe89a;',
		'icon-arrow-up9': '&#xe89b;',
		'icon-uniE89C': '&#xe89c;',
		'icon-arrow-left11': '&#xe89d;',
		'icon-arrow-down11': '&#xe89e;',
		'icon-arrow-up10': '&#xe89f;',
		'icon-arrow-right10': '&#xe8a0;',
		'icon-menu3': '&#xe8a1;',
		'icon-ellipsis': '&#xe8a2;',
		'icon-dots': '&#xe8a3;',
		'icon-dot': '&#xe8a4;',
		'icon-cc': '&#xe8a5;',
		'icon-cc-by': '&#xe8a6;',
		'icon-cc-nc': '&#xe8a7;',
		'icon-cc-nc-eu': '&#xe8a8;',
		'icon-cc-nc-jp': '&#xe8a9;',
		'icon-cc-sa': '&#xe8aa;',
		'icon-cc-nd': '&#xe8ab;',
		'icon-cc-pd': '&#xe8ac;',
		'icon-cc-zero': '&#xe8ad;',
		'icon-cc-share': '&#xe8ae;',
		'icon-cc-share2': '&#xe8af;',
		'icon-danielbruce': '&#xe8b0;',
		'icon-danielbruce2': '&#xe8b1;',
		'icon-github6': '&#xe8b2;',
		'icon-github7': '&#xe8b3;',
		'icon-flickr5': '&#xe8b4;',
		'icon-flickr6': '&#xe8b5;',
		'icon-vimeo4': '&#xe8b6;',
		'icon-vimeo5': '&#xe8b7;',
		'icon-twitter4': '&#xe8b8;',
		'icon-twitter5': '&#xe8b9;',
		'icon-facebook4': '&#xe8ba;',
		'icon-facebook5': '&#xe8bb;',
		'icon-facebook6': '&#xe8bc;',
		'icon-googleplus5': '&#xe8bd;',
		'icon-googleplus6': '&#xe8be;',
		'icon-pinterest3': '&#xe8bf;',
		'icon-pinterest4': '&#xe8c0;',
		'icon-tumblr3': '&#xe8c1;',
		'icon-tumblr4': '&#xe8c2;',
		'icon-linkedin2': '&#xe8c3;',
		'icon-linkedin3': '&#xe8c4;',
		'icon-dribbble4': '&#xe8c5;',
		'icon-dribbble5': '&#xe8c6;',
		'icon-stumbleupon3': '&#xe8c7;',
		'icon-stumbleupon4': '&#xe8c8;',
		'icon-lastfm3': '&#xe8c9;',
		'icon-lastfm4': '&#xe8ca;',
		'icon-rdio': '&#xe8cb;',
		'icon-rdio2': '&#xe8cc;',
		'icon-spotify': '&#xe8cd;',
		'icon-spotify2': '&#xe8ce;',
		'icon-qq': '&#xe8cf;',
		'icon-instagram2': '&#xe8d0;',
		'icon-dropbox': '&#xe8d1;',
		'icon-evernote': '&#xe8d2;',
		'icon-flattr2': '&#xe8d3;',
		'icon-skype2': '&#xe8d4;',
		'icon-skype3': '&#xe8d5;',
		'icon-renren': '&#xe8d6;',
		'icon-sina-weibo': '&#xe8d7;',
		'icon-paypal4': '&#xe8d8;',
		'icon-picasa': '&#xe8d9;',
		'icon-soundcloud3': '&#xe8da;',
		'icon-mixi': '&#xe8db;',
		'icon-behance': '&#xe8dc;',
		'icon-circles': '&#xe8dd;',
		'icon-vk': '&#xe8de;',
		'icon-smashing': '&#xe8df;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
