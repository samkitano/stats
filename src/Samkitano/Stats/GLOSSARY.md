 # GLOSSARY 
 
 ## PAGES:

 The number of "pages" viewed by visitors.
 Pages are usually HTML, PHP or ASP files, not images or other files requested as a result of
 loading a "Page" (like js,css... files)


 ## VISITS:

 Number of visits made by all visitors.
 Think "session" here, say a unique IP accesses a page,
 and then requests three other pages within an hour.
 All of the "pages" are included in the visit,
 therefore you should expect multiple pages per visit and multiple visits per unique visitor
 (assuming that some of the unique IPs are logged with more than an hour between requests)

 ## HITS:

 Any files requested from the server (including files that are "Pages")
 except those that match the SkipFiles config parameter.

 ## UNIQUE VISITOR:

 A unique visitor is a person or computer (host)
 that has made at least 1 hit on 1 page of your web site during the current period
 shown by the report. If this user makes several visits during this period,
 it is counted only once. Visitors are tracked by IP address,
 so if multiple users are accessing your site from the same IP
 (such as a home or office network),
 they will be counted as a single unique visitor.

 ## BANDWIDTH:

 Total number of bytes for pages, images and files downloaded by web browsing.
 Note 1: Of course, this number includes only traffic for web only
 (or mail only, or ftp only depending on value of LogType).
 Note 2: This number does not include technical header data size used inside
 the HTTP or HTTPS protocol or by protocols at a lower level (TCP, IP...).
 Because of two previous notes, this number is often lower than bandwith
 reported by your provider (your provider counts in most cases
 bandwitdh at a lower level and includes all IP and UDP traffic).

## ENTRY PAGE

First page viewed by a visitor during its visit.
Note: When a visit started at end of month to end at beginning of next month,
you might have an Entry page for the month report and no Exit pages.
That's why Entry pages can be different than Exit pages.

## EXIT PAGE

Last page viewed by a visitor during its visit.
Note: When a visit started at end of month to end at beginning of next month,
you might have an Entry page for the month report and no Exit pages.
That's why Entry pages can be different than Exit pages.

## SESSION DURATION

The time a visitor spent on your site for each visit.
Some Visits durations are 'unknown' because they can't always be calculated.
This is the major reason for this:
- Visit was not finished when 'update' occured.
- Visit started the last hour (after 23:00) of the last day of a month
(A technical reason prevents AWStats from calculating duration of such sessions).

## GRABBER

A browser that is used primarily for copying locally an entire site.
These include for example "teleport", "webcapture", "webcopier"...

## DIRECT ACCESS / BOOKMARK:

This number represent the number of hits or ratio of hits when a visit to
your site comes from a direct access.
This means the first page of your web site was called:
- By typing your URL on the web browser address bar
- By clicking on your URL stored by a visitor inside its favorites
- By clicking on your URL found everywhere but not another internet web pages
(a link in a document, an application, etc...)
- Clicking an URL of your site inside a mail is often counted here.

## ADD TO FAVOURITES

This value, available in the "miscellanous chart",
reports an estimated indicator that can be used to have an idea of the number of times a visitor
has added your web site into its favourite bookmarks.
The technical rules for that is the following formula:
Number of Add to Favourites = round((x+y) / r)
where
x = Number of hits made by IE browsers for "/anydir/favicon.ico",
with a referer field not defined, and with no 404 error code
y = Number of hits made by IE browsers for "/favicon.ico",
with a referer field not defined, with or without 404 error code
r = Ratio of hits made by IE browsers compared to hits made by all browsers (r <= 1)

As you can see in formula, only IE is used to count reliable "add",
the "Add to favourites" for other browsers are estimated using ratio of other
browsers usage compared to ratio of IE usage.
The reason is that only IE do a hit on favicon.ico nearly ONLY when a user add the
page to its favourites. The other browsers make often hits on this file also for other reasons
so we can't count one "hit" as one "add" since it might be a hit for another reason.
AWStats differentiate also hits with error and not to avoid counting multiple hits
made recursively in upper path when favicon.ico file is not found in deeper directory of path.
Note that this number is just an indicator that is in most case higher than true value.
The reason is that even IE browser sometimes make hit on favicon without an "Add to favourites"
action by a user.

## HTTP STATUS CODES

HTTP status codes are returned by web servers to indicate the status of a request.
Codes 200 and 304 are used to tell the browser the page can be viewed.
206 codes indicate partial downloading of content and is reported in the Downloads section.
All other codes generates hits and traffic 'not seen' by the visitor.
For example a return code 301 or 302 will tell the browser to ask another page.
The browser will do another hit and should finaly receive the page with a return code 200 and 304.
All codes that are 'unseen' traffic are isolated by AWStats in the HTTP Status report chart.
They are 3-digit codes where the first digit of this code identifies the class of the status
code and the remaining 2 digits correspond to the specific condition within the response class.
They are classified in 5 categories:

	1xx - informational
	2xx - successful
	3xx - redirection
	4xx - client error
	5xx - server error

1xx class - Informational
Informational status codes are provisional responses from the web server... they give the client a heads-up on what the server is doing. Informational codes do not indicate an error condition.
100 	100 Continue
The continue status code tells the browser to continue sending a request to the server.
101 	101 Switching Protocols
The server sends this response when the client asks to switch from HTTP/1.0 to HTTP/1.1

2xx class - Successful
This class of status code indicates that the client's request was received, understood, and successful.
200 	200 Successful
201 	201 Created
202 	202 Accepted
203 	203 Non-Authorative Information
204 	204 No Content
205 	205 Reset Content
206 	206 Partial Content
The partial content success code is issued when the server fulfills a partial GET request. This happens when the client is downloading a multi-part document or part of a larger file.
3xx class - Redirection
This code tells the client that the browser should be redirected to another URL in order to complete the request. This is not an error condition.
300 	300 Multiple Choices
301 	301 Moved Permanently
302 	302 Moved Temporarily
303 	303 See Other
304 	304 Not Modified
305 	305 Use Proxy
4xx class - Client Error
This status code indicates that the client has sent bad data or a malformed request to the server. Client errors are generally issued by the webserver when a client tries to gain access to a protected area using a bad username and password.
400 	400 Bad Request
401 	401 Unauthorized
402 	402 Payment Required
403 	403 Forbidden
404 	404 Not Found
405 	400 Method Not Allowed
406 	400 Not Acceptable
407 	400 Proxy Authentication Required
408 	400 Request Timeout
409 	409 Conflict
410 	410 Gone
411 	411 Length Required
412 	412 Precondition Failed
413 	413 Request Entity Too Long
414 	414 Request-URI Too Long
415 	415 Unsupported Media Type
5xx class - Server Error
This status code indicates that the client's request couldn't be succesfully processed due to some internal error in the web server. These error codes may indicate something is seriously wrong with the web server.
500 	500 Internal Server Error
An internal server error has caused the server to abort your request. This is an error condition that may also indicate a misconfiguration with the web server. However, the most common reason for 500 server errors is when you try to execute a script that has syntax errors.
501 	501 Not Implemented
This code is generated by a webserver when the client requests a service that is not implemented on the server. Typically, not implemented codes are returned when a client attempts to POST data to a non-CGI (ie, the form action tag refers to a non-executable file).
502 	502 Bad Gateway
The server, when acting as a proxy, issues this response when it receives a bad response from an upstream or support server.
503 	503 Service Unavailable
The web server is too busy processing current requests to listen to a new client. This error represents a serious problem with the webserver (normally solved with a reboot).
504 	504 Gateway Timeout
Gateway timeouts are normally issued by proxy servers when an upstream or support server doesn't respond to a request in a timely fashion.
505 	505 HTTP Version Not Supported
The server issues this status code when a client tries to talk using an HTTP protocol that the server doesn't support or is configured to ignore.

## SMTP STATUS CODES

SMTP status codes are returned by mail servers to indicate the status of a sending/receiving mail. The status code depends on mail server and preprocessor used to analyze log file.
All codes that are failure codes are isolated by AWStats in the SMTP Status report chart, enabled by the directives ShowSMTPErrorsStats in AWStats config file. You can decide which codes are successfull mail transfer that should not appear in this chart with the ValidSMTPCodes directive.
Here are values reported for most mail servers (This should also be values when mail log file is preprocessing with maillogconvert.pl).
SMTP Errors are classified in 3 categories:

	2xx/3xx - successful
	4xx - failure, asking sender to try later
	5xx - permanent failure

2xx/3xx class - Success
They are SMTP protocols successfull answers
200 	200 Non standard success response
Non standard success response
211 	211 System status, or system help reply
System status, or system help reply
214 	214 Help message
Help message
220 	220 Service ready
Service ready
221 	221 Service closing transmission channel
Service closing transmission channel
250 	250 Requested mail action taken and completed
Your ISP mail server have successfully executes a command and the DNS is reporting a positive delivery.
251 	251 User not local: will forward to
Your message to a specified email address is not local to the mail server, but it will accept and forward the message to a different recipient email address.
252 	252 Recipient cannot be verified
Recipient cannot be verified but mail server accepts the message and attempts delivery
354 	354 Start mail input and end with .
Indicates mail server is ready to accept the message or instruct your mail client to send the message body after the mail server have received the message headers.
4xx class - Temporary Errors
Those codes are temporary error message. They are used to tell client sender that an error occured but he can try to solve it but trying again, so in most cases, clients that receive such codes will keep the mail in their queue and will try again later.
421 	421 Service not available, closing transmission channel
This may be a reply to any command if the service knows it must shut down.
450 	450 Requested mail action not taken: mailbox busy or access denied
Your ISP mail server indicates that an email address does not exist or the mailbox is busy. It could be the network connection went down while sending, or it could also happen if the remote mail server does not want to accept mail from you for some reason i.e. (IP address, From address, Recipient, etc.)
451 	451 Requested mail action aborted: error in processing
Your ISP mail server indicates that the mailing has been interrupted, usually due to overloading from too many messages or transient failure is one in which the message sent is valid, but some temporary event prevents the successful sending of the message. Sending in the future may be successful.
452 	452 Requested mail action not taken: insufficient system storage
Your ISP mail server indicates, probable overloading from too many messages and sending in the future may be successful.
453 	453 Too many messages
Some mail servers have the option to reduce the number of concurrent connection and also the number of messages sent per connection. If you have a lot of messages queued up it could go over the max number of messages per connection. To see if this is the case you can try submitting only a few messages to that domain at a time and then keep increasing the number until you find the maximum number accepted by the server.
5xx class - Permanent Errors
This are permanent error codes. Mail transfer is definitly a failure. No other try will be done.
500 	500 Syntax error, command unrecognized or command line too long
501 	501 Syntax error in parameters or arguments
502 	502 Command not implemented
503 	503 Server encountered bad sequence of commands
504 	504 Command parameter not implemented
521 	521 does not accept mail or closing transmission channel
You must be pop-authenticated before you can use this SMTP server and you must use your mail address for the Sender/From field.
530 	530 Access denied
A sendmailism ?
550 	550 Requested mail action not taken (Relaying not allowed, Unknown recipient user, ...)
Sending an email to recipients outside of your domain are not allowed or your mail server does not know that you have access to use it for relaying messages and authentication is required. Or to prevent the sending of SPAM some mail servers will not allow (relay) send mail to any e-mail using another company’s network and computer resources.
551 	551 User not local: please try or Invalid Address: Relay request denied
552 	552 Requested mail action aborted: exceeded storage allocation
ISP mail server indicates, probable overloading from too many messages.
553 	553 Requested mail action not taken: mailbox name not allowed
Some mail servers have the option to reduce the number of concurrent connection and also the number of messages sent per connection. If you have a lot of messages queued up (being sent) for a domain, it could go over the maximum number of messages per connection and/or some change to the message and/or destination must be made for successful delivery.
554 	554 Requested mail action rejected: access denied
557 	557 Too many duplicate messages
Resource temporarily unavailable Indicates (probable) that there is some kind of anti-spam system on the mail server.
