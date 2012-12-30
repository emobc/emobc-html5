/**
 * @author Jonnie
 /**
 * add options:
 *
 * Auto get,
 * refresh time
 * styles
 * html wrapitt
 */
(function($){
	var defaultOptions = {};
	var thisWrap = '';
	var inCall = false;
	var jtwitterUserObject = {
		created_at: new Date(),
		description: '',
		favourites_count: 0,
		followers_count: 0,
		following: 0,
		id: 0,
		location: '',
		name: '',
		notifications: true,
		profile_background_color: '000000',
		profile_background_image_url: '',
		profile_background_tile: true,
		profile_image_url: '',
		profile_link_color: '000000',
		profile_sidebar_fill_color: '000000',
		profile_sidebar_border_color: '000000',
		isprotected: false,
		screen_name: '',
		statuses_count: 0,
		time_zone: '',
		url: '',
		utc_offset: 0,
		verified: false
	};
	
	var jtwitterMessageObject = {
		id: 0,
		text: '',
		created_at: new Date(),
		recipient_id: 0,
		recipient_screen_name: '',
		recipient: jtwitterUserObject,
		sender_id: 0,
		sender_screen_name: '',
		sender: jtwitterUserObject
	};
	
	var jtwitterTweetObject = {
		id: 0,
		text: '',
		created_at: new Date(),
		source: '',
		truncated: false,
		favorited: false,
		in_reply_to_screen_name: null,
		in_reply_to_status_id: null,
		in_reply_to_user_id: null,
		user: jtwitterTweetObject
	};
	
	var jtwitterRateObject = {
	    reset_time_in_seconds: 0,
	    reset_time: new Date(),
	    remaining_hits: 0,
	    hourly_limit: 0
	};
	
	var jtwitterTrendObject = {
	    as_of: new Date(),
	    trends: {
    	    name: '',
    	    url: ''	        
	    }
	};

	var jtwitterSearchObject = {
	    completed_in: 0,
	    max_id: 0,
	    next_page: '',
	    page: 0,
	    query: '',
	    refresh_url: '',
	    results: {
	        created_at: new Date(),
	        from_user: '',
	        from_user_id: 0,
	        id: 0,
	        iso_language_code: '',
	        profile_image_url: '',
	        source: '',
	        text: '',
	        to_user_id: 0
	    }
	};
	
	$.fn.jtwitter = function(url, options){
	
		thisWrap = $(this);
		
		defaultOptions = $.extend({
			defaultTimeline: 'public',
			autoFetch: true,
			user: {
				username: null,
				password: null
			},
			resultLocations: {
				debug: null,
				success: null,
				error: null,
				currentUser: null,
				rateStatus: null,
				deleteTweet: null,
				getFollowing: null,
				getFollowers: null,
				getFriendsFollowers: null,
				getFriendsTimeline: null,
				getPublicTimeline: null,
				getUserTimeline: null,
				getReplies: null,
				showTweet: null,
				postTweet: null,
				getMessages: null,
				deleteMessage: null,
				createMessage: null,
				getSentMessages: null,
				getFavorites: null,
				createFavorite: null,
				deleteFavorite: null,
				followMember: null,
				unfollowMember: null,
				confirmFollow: null,
				blockMember: null,
				unblockMember: null,
				getAllFollowers: null,
				getAllFriends: null,
				getRateLimit: null,
				endSession: null,
				verifyCredentials: null,
				updateDevice: null,
				updateLocation: null,
				updateProfile: null,
				updateProfileImage: null,
				updateBackgroundImage: null,
				updateProfileColors: null,
				searchKeywords: null,
				searchTrends: null
			}
		}, options);
		
		$.jtwitter = {
			jtwitterMethods: ["deleteTweet", "getFollowing", "getFollowers", "getFriendsFollowers", "getFriendsTimeline", "getPublicTimeline", "getUserTimeline", "getReplies", "showTweet", "postTweet", "showProfile", "getMessages", "deleteMessage", "createMessage", "getSentMessages", "followMember", "unfollowMember", "confirmFollow", "getFavorites", "createFavorite", "deleteFavorite", "blockMember", "unBlockMember", "getAllFriends", "getAllFollowers", "getRateLimit", "endSession", "verifyCredentials", "updateDevice", "updateLocation", "updateProfile", "updateBackgroundImage", "updateProfileColors", "updateProfileImage", "notification_turnOn", "notification_turnOff", "search_keywords", "search_trends", "help_test"],
			
			/**
			 * I make a get call to the proxy which then calls on the twitter api.
			 * @param {Object} method - the method to invoke
			 * @param {Object} params - the parameter object to send with the call
			 * @param {Object} callback - the callback to execute once result is recieved
			 */
			_get: function(method, params, callback){
				$.extend(params, {
					m: method
				});
				
				if (defaultOptions.user.username) {
					$.extend(params, {
						u: defaultOptions.user.username
					});
				}
				
				if (defaultOptions.user.password) {
					$.extend(params, {
						p: defaultOptions.user.password
					});
				}
				
				//Check if we are in a call,
				
				
				$.get(url, params, function(result){
					//Make json to array
					var resultArray = eval('(' + result + ')');
					if (callback) {
						callback(resultArray);
					}
					
					//return resultArray;
				});
			},
			
			/**
			 * I post data to the proxy which then posts to twitter.com
			 *
			 * @param {Object} method - the method to invoke
			 * @param {Object} params - the parameter object to send with the call
			 * @param {Object} callback - the callback to execute once result is recieved
			 */
			_post: function(method, params, callback){
				$.extend(params, {
					m: method,
					u: defaultOptions.user.username,
					p: defaultOptions.user.password
				});
				
				$.post(url, params, function(rawjson){
					var resultArray = eval(rawjson);
					if (callback) {
						callback(resultArray);
					}
				});
			},
			
			
			/**
			 * I am the methods object, I hold all of the methods that are available on the proxy.
			 * I then call upon the _get function passing all required parameters, and then handle
			 * the result by passing the result and the method called to the build function. Which
			 * then populates the specified jquery object with the formatted and build data.
			 */
			getPublicTimeline: function(){
				$.jtwitter._get('getPublicTimeline', {}, function(data){
					(defaultOptions.resultLocations.getPublicTimeline) ? 
					$(defaultOptions.resultLocations.getPublicTimeline).html($.jtwitter._builders.build('tweet', data)) : 
					$(thisWrap).html($.jtwitter._builders.build('tweet', data));
				});
			},
			
			getFollowers: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getFollowers', {}, function(data){
						(defaultOptions.resultLocations.getFollowers) ? 
    					$(defaultOptions.resultLocations.getFollowers).html($.jtwitter._builders.build('follower', data)) : 
    					$(thisWrap).html($.jtwitter._builders.build('follower', data));
					});
				}
			},
			
			getFollowing: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getFollowing', {}, function(data){
							(defaultOptions.resultLocations.getFollowing) ? 
        					$(defaultOptions.resultLocations.getFollowing).html($.jtwitter._builders.build('follower', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('follower', data));
					});
				}
			},
			
			getFriendsFollowers: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getFriendsFollowers', {}, function(data){
						(defaultOptions.resultLocations.getFriendsFollowers) ? 
    					$(defaultOptions.resultLocations.getFriendsFollowers).html($.jtwitter._builders.build('tweet', data)) : 
    					$(thisWrap).html($.jtwitter._builders.build('tweet', data));
					});
				}
			},
			
			getFriendsTimeline: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getFriendsTimeline', {}, function(data){
				    	(defaultOptions.resultLocations.getFriendsTimeline) ? 
    					$(defaultOptions.resultLocations.getFriendsTimeline).html($.jtwitter._builders.build('tweet', data)) : 
    					$(thisWrap).html($.jtwitter._builders.build('tweet', data));
					});
				}
				
			},
			
			
			getUserTimeline: function(user){
			
				$.jtwitter._get('getUserTimeline', {}, function(data){			
					(defaultOptions.resultLocations.getUserTimeline) ? 
					$(defaultOptions.resultLocations.getUserTimeline).html($.jtwitter._builders.build('tweet', data)) : 
					$(thisWrap).html($.jtwitter._builders.build('tweet', data));
				});
				
			},
			
			getReplies: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getReplies', {}, function(data){
					    	(defaultOptions.resultLocations.getReplies) ? 
        					$(defaultOptions.resultLocations.getReplies).html($.jtwitter._builders.build('tweet', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('tweet', data));
					});
				}
			},
			
			showTweet: function(id){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('showTweet', {
						id: id
					}, function(data){
						$(defaultOptions.resultLocations.showTweet).html($.jtwitter._builders.build('tweet', data));
						(defaultOptions.resultLocations.showTweet) ? 
						$(defaultOptions.resultLocations.getReplies).html($.jtwitter._builders.build('tweet', data)) : null;
					});
				}
			},
			
			postTweet: function(text){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('postTweet', {
						status: text
					}, function(data){
							(defaultOptions.resultLocations.postTweet) ? 
        					$(defaultOptions.resultLocations.postTweet).prepend($.jtwitter._builders.build('tweet', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('tweet', data));
					});
				}
			},
			
			getMessages: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getMessages', {}, function(data){
							(defaultOptions.resultLocations.getMessages) ? 
        					$(defaultOptions.resultLocations.getMessages).html($.jtwitter._builders.build('message', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('message', data));
					});
				}
			},
			
			getSentMessages: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getSentMessages', {}, function(data){
							(defaultOptions.resultLocations.getPublicTimeline) ? 
        					$(defaultOptions.resultLocations.getPublicTimeline).html($.jtwitter._builders.build(1, data)) : 
        					$(thisWrap).html($.jtwitter._builders.build(1, data));
					});
				}
			},
			
			createMessage: function(userid, text){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('createMessage', {
						id: userid,
						text: text
					}, function(data){
					    	(defaultOptions.resultLocations.createMessage) ? 
        					$(defaultOptions.resultLocations.createMessage).html($.jtwitter._builders.build('message', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('message', data));
					});
				}
			},
			
			deleteMessage: function(msgid){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('deleteMessage', {
						id: msgid
					}, function(data){
							(defaultOptions.resultLocations.deleteMessage) ? 
        					$(defaultOptions.resultLocations.deleteMessage).html($.jtwitter._builders.build('message', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('message', data));
					});
				}
			},
			
			getFavorites: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getFavorites', {}, function(data){
							(defaultOptions.resultLocations.getFavorites) ? 
        					$(defaultOptions.resultLocations.getFavorites).html($.jtwitter._builders.build('tweet', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('tweet', data));
					});
				}
			},
			
			createFavorite: function(msgid){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('createFavorite', {
						id: msgid
					}, function(data){
							(defaultOptions.resultLocations.createFavorite) ? 
        					$(defaultOptions.resultLocations.createFavorite).html($.jtwitter._builders.build('tweet', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('tweet', data));
					});
				}
			},
			
			deleteFavorite: function(msgid){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('deleteFavorite', {
						id: msgid
					}, function(data){
							(defaultOptions.resultLocations.deleteFavorite) ? 
        					$(defaultOptions.resultLocations.deleteFavorite).html($.jtwitter._builders.build('tweet', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('tweet', data));
					});
				}
			},
			
			followMember: function(userid, follow){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('followMember', {
						id: userid,
						follow: follow
					}, function(data){
							(defaultOptions.resultLocations.followMember) ? 
        					$(defaultOptions.resultLocations.followMember).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			unfollowMember: function(userid){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('unfollowMember', {
						id: userid
					}, function(data){
							(defaultOptions.resultLocations.unfollowMember) ? 
        					$(defaultOptions.resultLocations.unfollowMember).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			confirmFollow: function(usera, userb){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('confirmFollow', {
						a: usera,
						b: userb
					}, function(data){
							(defaultOptions.resultLocations.confirmFollow) ? 
        					$(defaultOptions.resultLocations.confirmFollow).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			blockMember: function(userid){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('blockMember', {
						id: userid
					}, function(data){
							(defaultOptions.resultLocations.blockMember) ? 
        					$(defaultOptions.resultLocations.blockMember).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			unblockMember: function(userid){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('unblockMember', {
						id: userid
					}, function(data){
					    	(defaultOptions.resultLocations.unblockMember) ? 
        					$(defaultOptions.resultLocations.unblockMember).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			getAllFollowers: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getAllFollowers', {}, function(data){
							(defaultOptions.resultLocations.getAllFollowers) ? 
        					$(defaultOptions.resultLocations.getAllFollowers).html($.jtwitter._builders.build('countfriends', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('countfriends', data));
					});
				}
			},
			
			getAllFriends: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getAllFriends', {}, function(data){
							(defaultOptions.resultLocations.getAllFriends) ? 
        					$(defaultOptions.resultLocations.getAllFriends).html($.jtwitter._builders.build('countfriends', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('countfriends', data));
					});
				}
			},
			
			getRateLimit: function(){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._get('getRateLimit', {}, function(data){
					    	(defaultOptions.resultLocations.getRateLimit) ? 
        					$(defaultOptions.resultLocations.getRateLimit).html($.jtwitter._builders.build('rate', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('rate', data));
					});
				}
			},
			
			endSession: function(){
				$.jtwitter._post('endSession', {}, function(data){
						(defaultOptions.resultLocations.endSession) ? 
    					$(defaultOptions.resultLocations.endSession).html($.jtwitter._builders.build('user', data)) : 
    					$(thisWrap).html($.jtwitter._builders.build('user', data));
				});
			},
			
			verifyCredentials: function(){
				$.jtwitter._get('verifyCredentials', {}, function(data){
						(defaultOptions.resultLocations.verifyCredentials) ? 
    					$(defaultOptions.resultLocations.verifyCredentials).html($.jtwitter._builders.build('user', data)) : 
    					$(thisWrap).html($.jtwitter._builders.build('user', data));
				});
			},
			
			updateDevice: function(device){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('updateDevice', {
						device: device
					}, function(data){
							(defaultOptions.resultLocations.updateDevice) ? 
        					$(defaultOptions.resultLocations.updateDevice).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			updateLocation: function(location){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('updateLocation', {
						location: location
					}, function(data){
							(defaultOptions.resultLocations.updateLocation) ? 
        					$(defaultOptions.resultLocations.updateLocation).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			updateProfile: function(name, email, url, location, desc){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('updateProfile', {
						n: name,
						e: email,
						u: url,
						l: location,
						d: desc
					}, function(data){
							(defaultOptions.resultLocations.updateProfile) ? 
        					$(defaultOptions.resultLocations.updateProfile).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			updateProfileImage: function(img){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('updateProfileImage', {
						image: img
					}, function(data){
							(defaultOptions.resultLocations.updateProfileImage) ? 
        					$(defaultOptions.resultLocations.updateProfileImage).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			updateBackgroundImage: function(img){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('updateBackgroundImage', {
						image: img
					}, function(data){
							(defaultOptions.resultLocations.updateBackgroundImage) ? 
        					$(defaultOptions.resultLocations.updateBackgroundImage).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			updateProfileColors: function(bg, text, link, sidebg, sideborder){
				if ($.jtwitter._utils.checkUser()) {
					$.jtwitter._post('updateProfileColors', {
						bg: bg,
						t: text,
						sbg: sidebg,
						sb: sideborder
					}, function(data){
							(defaultOptions.resultLocations.updateProfileColors) ? 
        					$(defaultOptions.resultLocations.updateProfileColors).html($.jtwitter._builders.build('user', data)) : 
        					$(thisWrap).html($.jtwitter._builders.build('user', data));
					});
				}
			},
			
			searchKeywords: function(q){
				$.jtwitter._get('searchKeywords', {
					q: q
				}, function(data){
						(defaultOptions.resultLocations.searchKeywords) ? 
    					$(defaultOptions.resultLocations.searchKeywords).html($.jtwitter._builders.build('search', data)) : 
    					$(thisWrap).html($.jtwitter._builders.build('search', data));
				});
			},
			
			searchTrends: function(){
				$.jtwitter._get('searchTrends', {}, function(data){
						(defaultOptions.resultLocations.searchTrends) ? 
    					$(defaultOptions.resultLocations.searchTrends).html($.jtwitter._builders.build('trends', data)) : 
    					$(thisWrap).html($.jtwitter._builders.build('trends', data));
				});
			},
			
			
			
			/**
			 * I hold all of the functions that build the html from the result of a service call
			 */
			_builders: {
				/**
				 * I evaluate and prep each build depending on what method was invoked
				 * @param {Object} mode - what method that was called
				 * @param {Object} data - the result object from that method
				 */
				build: function(mode, data){
					var html = '';
					switch (mode) {
						case 'tweet'://Timelines, Replies, Favorites,
							$.each(data, function(i, obj){
								$.extend(jtwitterTweetObject, obj);
								html += $.jtwitter._builders.buildTweets(jtwitterTweetObject);
							});
						break;
							
						case 'message'://Messages, Sent Messages
							$.each(data, function(i, obj){
								$.extend(jtwitterMessageObject, obj);
								html += $.jtwitter._builders.buildMessages(jtwitterMessageObject);
							});
							
						break;
							
						case 'follower'://Followers Image List
							$.each(data, function(i, obj){
								$.extend(jtwitterUserObject, obj);
								html += $.jtwitter._builders.buildImages(jtwitterUserObject);
							});
							
						break;
							
						case 'user'://User Update Status, Profile changes, etc user 
							$.each(data, function(i, obj){
								$.extend(jtwitterUserObject, obj);
								html += $.jtwitter._builders.buildUser(jtwitterUserObject);
							});
							
						break;
						
						case 'search'://User Update Status, Profile changes, etc user 
							$.extend(jtwitterSearchObject, data);
							$.each(jtwitterSearchObject.results, function(i, obj){
								html += $.jtwitter._builders.buildSearch(obj);
							});

						break;
						
						case 'trends'://User Update Status, Profile changes, etc user 
							$.extend(jtwitterTrendObject, data);
							$.each(jtwitterTrendObject.trends, function(i, obj){
								html += $.jtwitter._builders.buildTrends(obj);
							});

						break;
							
						case 'rate': //Rate Status 
								$.extend(jtwitterRateObject, data);
								html += $.jtwitter._builders.buildRateStatus(jtwitterRateObject);
						break;
					}
					
					
					return html;
				},
				/**
				 * I build the html for the messages
				 * @param {Object} messageObj
				 */
				buildTweets: function(tweetObj){
				
					var html = '';
					html += '<li class="jtwitter-tweet" id="jtwitter-' + tweetObj.id + '">';
					html += '<ul>';
					html += '<li class="jtwitter-profile-image"><img src="' + tweetObj.user.profile_image_url + '" alt="Photo of ' + tweetObj.user.name + '"/></li>';
					html += '<li class="jtwitter-screen-name">';
					html += '<a class="jtwitter-url" href="http://twitter.com/' + tweetObj.user.screen_name + '" title="' + tweetObj.user.screen_name + '" rel="bookmark" target="_blank">';
					html += tweetObj.user.screen_name + '</a></li>';					
					html += '<li class="jtwitter-text">' + tweetObj.text + '</li>';
					html += '<li class="jtwitter-meta">';
					html += '<span class="jtwitter-created-at">' + $.jtwitter._utils.prettifyDate(tweetObj.created_at) + ' </span>';
					html += '<span class="jtwitter-source">via ' + tweetObj.source + '</span>';
					html += '</li>';
					html += '<li class="jtwitter-meta">';
					html += '<span class="jtwitter-friends">' + tweetObj.user.friends_count + ' friends</span> / ';
					html += '<span class="jtwitter-followers">' + tweetObj.user.followers_count + ' followers</span> / ';
					html += '<span class="jtwitter-updates">' + tweetObj.user.statuses_count + ' updates</span> / ';
					html += '<span class="jtwitter-favorites">' + tweetObj.user.favourites_count + ' favorites</span>';
					html += '</li>';	
					html += '</ul>';
					html += '</li>';
					
					return html;
				},

			buildMessages: function(msgObj){
					var html = '';
                    html += '<li class="jtwitter-tweet" id="jtwitter-' + msgObj.id + '"><li>';
					html += '<ul class="jtwitter-profile-image"><img src="' + msgObj.sender.profile_image_url + '" alt="Photo of ' + msgObj.sender.name + '"/></li>';
					html += '<li class="jtwitter-screen-name">';
					html += '<a class="jtwitter-url" href="http://twitter.com/' + msgObj.sender.screen_name + '" title="' + msgObj.sender.screen_name + '" rel="bookmark" target="_blank">';
					html += msgObj.sender.screen_name + '</a></li>';
					html += '<li class="jtwitter-text">' + msgObj.text + '</li>';
					html += '<li class="jtwitter-meta">';
					html += '<span class="jtwitter-created-at">' + $.jtwitter._utils.prettifyDate(msgObj.created_at) + ' </span>';
					html += '</li></ul></li>';
 
					return html;
				
			},
			buildImages: function(userObj){
					var html = '<li id="jtwitter-user-'+userObj.id+'" class="jtwitter-profile-image">';
						html += '<a class="jtwitter-url" href="http://twitter.com/' + userObj.screen_name + '" title="' + userObj.screen_name + '" rel="bookmark" target="_blank">';
						html += '<img src="' + userObj.profile_image_url + '" alt="Photo of ' + userObj.name + '"/></a></li>';
					  
					return html;
			},
			buildTrends: function(trendObj){
					var html = '';
						html += '<li class="jtwitter-trend"><a href="' + trendObj.url + '">' + trendObj.name + '</a></li>';
					  
					return html;
			},
			buildSearch: function(searchObj){
					var html = '';
						html += '<li class="jtwitter-search">'+searchObj.text+'</li>';

					return html;
			},
			buildUser: function(userObj){
					var html = '';
					html += '<li class="jtwitter-tweet" id="jtwitter-user-' + userObj.id + '">';
					html += '<ul>';
					html += '<li class="jtwitter-profile-image"><img src="' + userObj.profile_image_url + '" alt="Photo of ' + userObj.name + '"/></li>';
					html += '<li class="jtwitter-screen-name">';
					html += '<a class="jtwitter-url" href="http://twitter.com/' + userObj.screen_name + '" title="' + userObj.screen_name + '" rel="bookmark" target="_blank">';
					html += userObj.screen_name + '</a></li>';
					html += '<li class="jtwitter-meta">';
					html += '<span class="jtwitter-friends">' + userObj.friends_count + ' friends</span> / ';
					html += '<span class="jtwitter-followers">' + userObj.followers_count + ' followers</span> / ';
					html += '<span class="jtwitter-updates">' + userObj.statuses_count + ' updates</span> / ';
					html += '<span class="jtwitter-favorites">' + userObj.favourites_count + ' favorites</span>';
					html += '</li>';	
					html += '</ul>';
					html += '</li>';
					    
					    
					return html;
			},
			buildRateStatus: function(statusObj){
				var html = '';
					html += '<li>Remaining Hits: '+ statusObj.remaining_hits +'</li>';
					html += '<li>Hourly Limit: '+ statusObj.hourly_limit +'</li>';
					html += '<li>Reset Time: '+ statusObj.reset_time +'</li>';
				return html;
			}

			},
			_utils: {
				prettifyDate: function(time){
				
					var values = time.split(" ");
					time = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
					var parsed_date = Date.parse(time);
					var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
					var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
					delta = delta + (relative_to.getTimezoneOffset() * 60);
					
					var out = '';
					if (delta < 60) {
						out = 'a minute ago';
					} else if (delta < 120) {
						out = 'couple of minutes ago';
					} else if (delta < (45 * 60)) {
						out = (parseInt(delta / 60)).toString() + ' minutes ago';
					} else if (delta < (90 * 60)) {
						out = 'an hour ago';
					} else if (delta < (24 * 60 * 60)) {
						out = '' + (parseInt(delta / 3600)).toString() + ' hours ago';
					} else if (delta < (48 * 60 * 60)) {
						out = '1 day ago';
					} else {
						out = (parseInt(delta / 86400)).toString() + ' days ago';
					}
					
					return out;
					
				},
				checkUser: function(){
				
					if (defaultOptions.user.username != null && defaultOptions.user.password != null) {
						return true;
					} else {
						alert('You must login for this call.');
						return false;
					}
				}
			}
		
		};//ends jTwitterObject
		//If auto load data is true
		if (defaultOptions.autoFetch) {
			if (defaultOptions.defaultTimeline == 'public') {
				$.jtwitter.getPublicTimeline();
			} else if (defaultOptions.defaultTimeline == 'user') {
				$.jtwitter.getUserTimeline();
			} else if (defaultOptions.defaultTimeline == 'friends') {
				$.jtwitter.getFriendsTimeline();
			}
		}
		
		return thisWrap;
		
	};
})(jQuery);

