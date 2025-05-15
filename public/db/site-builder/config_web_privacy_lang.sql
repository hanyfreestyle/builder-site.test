SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `config_web_privacy_lang` (`id`, `privacy_id`, `locale`, `h1`, `h2`, `des`, `lists`) VALUES
('3', '2', 'ar', 'شروط وسياسة الخصوصية', '', 'تقوم شركة [CompanyName] (\"نحن\" أو \"نحن\" أو \"موقعنا\") بتشغيل [WebSiteName]  (\"الموقع الالكترونى\").
تُعرفك هذه الصفحة بسياساتنا المتعلقة بجمع البيانات الشخصية واستخدامها والكشف عنها عند استخدامك للخدمة والأختيارات المرتبطة بهذه البيانات. 
سياسة الخصوصية لشركة [CompanyName]
نستخدم بياناتك لتوفير الخدمة وتحسينها. باستخدام الخدمة، فإنك توافق على جمع واستخدام المعلومات وفقًا لهذه السياسة. ما لم يتم تحديد خلاف ذلك في سياسة الخصوصية، فإن المصطلحات المستخدمة في سياسة الخصوصية لها نفس المعاني كما في الشروط والأحكام الخاصة بنا، والتي يمكن الوصول إليها من [WebSiteName]', ''),
('4', '2', 'en', 'Privacy Policy', '', ' [CompanyName] (\"us\", \"we\", or \"our\") operates the [WebSiteName] website (the \"Service\").
This page informs you of our policies regarding the collection, use, and disclosure of personal data when you use our Service and the choices you have associated with that data. Our Privacy Policy for [CompanyName]
We use your data to provide and improve the Service. By using the Service, you agree to the collection and use of information in accordance with this policy. Unless otherwise defined in this Privacy Policy, terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, accessible from  [WebSiteName]', ''),
('5', '3', 'ar', 'جمع المعلومات واستخدامها', '', 'نقوم بتجميع أنواع مختلفة من المعلومات لأغراض متنوعة لتوفير وتحسين خدماتنا لك.', ''),
('6', '3', 'en', 'Information Collection And Use', '', 'We collect several different types of information for various purposes to provide and improve our Service to you.', ''),
('7', '4', 'ar', 'أنواع البيانات المجمعة', 'بيانات شخصية', 'أثناء استخدام خدماتنا ، قد نطلب منك تزويدنا بمعلومات تعريف شخصية معينة يمكن استخدامها للاتصال أو التعرف عليك (&quot;البيانات الشخصية&quot;). قد تتضمن معلومات 
التعريف الشخصية ، على سبيل المثال لا الحصر ، ما يلي:', 'عنوان بريد الكتروني
الاسم الأول واسم العائلة
رقم الهاتف
العنوان ، الولاية ، المقاطعة ، الرمز البريدي / المدينة ، المدينة
ملفات تعريف الارتباط وبيانات الاستخدام'),
('8', '4', 'en', 'Types of Data Collected', 'Personal Data', 'While using our Service, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you (&quot;Personal Data&quot;). Personally identifiable information may include, but is not limited to :', 'Email address
First name and last name
Phone number
Address, State, Province, ZIP/Postal code, City
Cookies and Usage Data'),
('9', '5', 'ar', 'بيانات الاستخدام', '', 'يجوز لنا أيضًا جمع المعلومات حول كيفية الوصول إلى الخدمة واستخدامها (&quot;بيانات الاستخدام&quot;). قد تتضمن بيانات الاستخدام هذه معلومات مثل عنوان بروتوكول الإنترنت الخاص بجهاز الكمبيوتر (مثل عنوان IP) ، ونوع المتصفح، وإصدار المتصفح، وصفحات الخدمة التي تزورها، ووقت وتاريخ زيارتك، والوقت الذي يقضيه في تلك الصفحات، ومعرفات الجهاز وغيرها من البيانات التشخيصية.', ''),
('10', '5', 'en', 'Usage Data', '', 'We may also collect information how the Service is accessed and used (&quot;Usage Data&quot;). This Usage Data may include information such as your computer\'s Internet Protocol address (e.g. IP address), browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages, unique device identifiers and other diagnostic data.', ''),
('11', '6', 'ar', 'تتبع و ملفات تعريف الارتباط', '', 'نحن نستخدم ملفات تعريف الارتباط وتقنيات التتبع المماثلة لتتبع النشاط على الخدمة لدينا مع الاحتفاظ بمعلومات معينة.
ملفات تعريف الارتباط عبارة عن ملفات تحتوي على كمية صغيرة من البيانات التي قد تتضمن معرفًا فريدًا مجهول الهوية. يتم إرسال ملفات تعريف الارتباط إلى متصفحك من موقع الويب وتخزينها على جهازك. تقنيات التتبع المستخدمة هي منارات وعلامات ونصوص لجمع المعلومات وتتبعها ولتحسين خدمتنا وتحليلها.
يمكنك إرشاد المتصفح الخاص بك لرفض جميع ملفات تعريف الارتباط أو للإشارة إلى إرسال ملف تعريف الارتباط. ومع ذلك، إذا كنت لا تقبل ملفات تعريف الارتباط، فقد لا تتمكن من استخدام بعض أجزاء من خدمتنا.

أمثلة على ملفات تعريف الارتباط التي نستخدمها:', 'نحن نستخدم ملفات تعريف الارتباط الخاصة بالجلسات لتشغيل الخدمة الخاصة بنا.
نحن نستخدم ملفات تعريف الارتباط التفضيلية لتذكر تفضيلاتك والإعدادات المختلفة.
نحن نستخدم ملفات تعريف الارتباط للأمان لأغراض أمنية.'),
('12', '6', 'en', 'Tracking &amp; Cookies Data', '', 'We use cookies and similar tracking technologies to track the activity on our Service and hold certain information.
Cookies are files with small amount of data which may include an anonymous unique identifier. Cookies are sent to your browser from a website and stored on your device. Tracking technologies also used are beacons, tags, and scripts to collect and track information and to improve and analyze our Service.
You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.

Examples of Cookies we use:', 'Session Cookies. We use Session Cookies to operate our Service.
Preference Cookies. We use Preference Cookies to remember your preferences and various settings.
Security Cookies. We use Security Cookies for security purposes.'),
('13', '7', 'ar', 'استخدام البيانات', '', 'تستخدم [CompanyName] البيانات التي تم جمعها لأغراض مختلفة :', 'لإعلامك عن تغييرات لخدمتنا.
للسماح لك بالمشاركة في الميزات التفاعلية في خدمتنا عندما تختار القيام بذلك.
لتوفير رعاية العملاء والدعم.
لتقديم تحليل أو معلومات قيمة حتى نتمكن من تحسين الخدمة.
لمراقبة استخدام الخدمة.
للكشف عن المشكلات الفنية ومنعها ومعالجتها.'),
('14', '7', 'en', 'Use of Data', '', '[CompanyName] uses the collected data for various purposes:', 'To provide and maintain the Service.
To notify you about changes to our Service.
To allow you to participate in interactive features of our Service when you choose to do so.
To provide customer care and support.
To provide analysis or valuable information so that we can improve the Service.
To monitor the usage of the Service.
To detect, prevent and address technical issues.'),
('15', '8', 'ar', 'نقل البيانات', '', 'قد يتم نقل معلوماتك - بما في ذلك البيانات الشخصية - إلى أجهزة الكمبيوتر الموجودة خارج الولاية أو المقاطعة أو الدولة أو الولاية الحكومية الأخرى التي قد تختلف فيها قوانين حماية البيانات عن تلك الخاصة باختصاصك القضائي.
إذا كنت متواجدًا خارج مصر واخترت تقديم معلومات لنا، يرجى ملاحظة أننا نقوم بنقل البيانات، بما في ذلك البيانات الشخصية، إلى مصر ومعالجتها هناك.
إن موافقتك على سياسة الخصوصية هذه والتي يتبعها تقديمك لهذه المعلومات تمثل موافقتك على هذا النقل 
سوف تتخذ [CompanyName] جميع الخطوات الضرورية بشكل معقول لضمان التعامل مع بياناتك بشكل آمن ووفقًا لسياسة الخصوصية هذه ولن يتم نقل بياناتك الشخصية إلى منظمة أو دولة ما لم تكن هناك ضوابط كافية في مكان بما في ذلك أمن البيانات الخاصة بك وغيرها من المعلومات الشخصية.', ''),
('16', '8', 'en', 'Transfer Of Data', '', 'Your information, including Personal Data, may be transferred to — and maintained on — computers located outside of your state, province, country or other governmental jurisdiction where the data protection laws may differ than those from your jurisdiction.
If you are located outside Egypt and choose to provide information to us, please note that we transfer the data, including Personal Data, to Egypt and process it there.
Your consent to this Privacy Policy followed by your submission of such information represents your agreement to that transfer.
[CompanyName] will take all steps reasonably necessary to ensure that your data is treated securely and in accordance with this Privacy Policy and no transfer of your Personal Data will take place to an organization or a country unless there are adequate controls in place including the security of your data and other personal information.', ''),
('17', '9', 'ar', 'الكشف عن البيانات', 'المتطلبات القانونية', 'يحق لـ [CompanyName] الإفصاح عن بياناتك الشخصية بحسن نية من أن هذا الإجراء ضروري من أجل:', 'للامتثال لالتزام قانوني
لحماية والدفاع عن حقوق أو ملكية [CompanyName]
لمنع أو التحقيق في أي مخالفات محتملة تتعلق بالخدمة.
لحماية السلامة الشخصية لمستخدمي الخدمة أو الجمهور.
للحماية من المسؤولية القانونية.'),
('18', '9', 'en', 'Disclosure Of Data', 'Legal Requirements', '[CompanyName] may disclose your Personal Data in the good faith belief that such action is necessary to:', 'To comply with a legal obligation.
To protect and defend the rights or property of [CompanyName]
To prevent or investigate possible wrongdoing in connection with the Service.
To protect the personal safety of users of the Service or the public.
To protect against legal liability.'),
('19', '10', 'ar', 'أمن البيانات', '', 'أمان بياناتك مهم بالنسبة لنا، ولكن تذكر أنه لا توجد طريقة للإرسال عبر الإنترنت، أو طريقة التخزين الإلكترونية آمنة ١٠٠٪. بينما نسعى جاهدين لاستخدام وسائل مقبولة تجاريًا لحماية بياناتك الشخصية، لا يمكننا ضمان أمانها المطلق.', ''),
('20', '10', 'en', 'Security Of Data', '', 'The security of your data is important to us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your Personal Data, we cannot guarantee its absolute security.', ''),
('21', '11', 'ar', 'مقدمي الخدمة', '', 'يجوز لنا أن نوظف شركات وأفراد من أطراف ثالثة لتسهيل خدمتنا (&quot;مزودي الخدمة&quot;)، أو تقديم الخدمة نيابة عنا، لأداء الخدمات المتعلقة بالخدمة أو لمساعدتنا في تحليل كيفية استخدام خدمتنا.
هذه الأطراف الثالثة لديها حق الوصول إلى بياناتك الشخصية فقط لأداء هذه المهام نيابة عنا وتكون ملزمة بعدم الكشف عنها أو استخدامها لأي غرض آخر.', ''),
('22', '11', 'en', 'Service Providers', '', 'We may employ third party companies and individuals to facilitate our Service (&quot;Service Providers&quot;), to provide the Service on our behalf, to perform Service-related services or to assist us in analyzing how our Service is used.
These third parties have access to your Personal Data only to perform these tasks on our behalf and are obligated not to disclose or use it for any other purpose.', ''),
('23', '12', 'ar', 'تحليلات', '', 'قد نستخدم مزودي خدمة من جهات خارجية لمراقبة وتحليل استخدام خدماتنا.', ''),
('24', '12', 'en', 'Analytics', '', 'We may use third-party Service Providers to monitor and analyze the use of our Service.', ''),
('25', '13', 'ar', 'روابط لمواقع أخرى', '', 'قد تحتوي خدمتنا على روابط إلى مواقع أخرى لا يتم تشغيلها من قبلنا. إذا نقرت على رابط جهة خارجية، فسيتم توجيهك إلى موقع الطرف الثالث هذا. ننصحك بشدة بمراجعة سياسة الخصوصية لكل موقع تزوره.

ليس لدينا أي سيطرة ولا نتحمل أي مسؤولية عن المحتوى أو سياسات الخصوصية أو الممارسات الخاصة بأي مواقع أو خدمات خاصة بطرف ثالث.', ''),
('26', '13', 'en', 'Links To Other Sites', '', 'Our Service may contain links to other sites that are not operated by us. If you click on a third party link, you will be directed to that third party\'s site. We strongly advise you to review the Privacy Policy of every site you visit.

We have no control over and assume no responsibility for the content, privacy policies or practices of any third party sites or services.', ''),
('27', '14', 'ar', 'خصوصية الأطفال', '', 'لا تتناول خدمتنا أي شخص دون سن ١٨ عامًا (&quot;الأطفال&quot;).
نحن لا نجمع معلومات التعريف الشخصية من أي شخص دون سن ١٨ عامًا. إذا كنت أحد الوالدين أو الوصي وكنت على علم بأن أطفالك قد زودونا ببيانات شخصية، يرجى الاتصال بنا. إذا علمنا أننا جمعنا بيانات شخصية من الأطفال دون التحقق من موافقة الوالدين، فإننا نتخذ خطوات لإزالة تلك المعلومات من خوادمنا.', ''),
('28', '14', 'en', 'Children\'s Privacy', '', 'Our Service does not address anyone under the age of 18 (&quot;Children&quot;).
We do not knowingly collect personally identifiable information from anyone under the age of 18. If you are a parent or guardian and you are aware that your Children has provided us with Personal Data, please contact us. If we become aware that we have collected Personal Data from children without verification of parental consent, we take steps to remove that information from our servers.', ''),
('29', '15', 'ar', 'تغييرات سياسة الخصوصية', '', 'يجوز لنا تحديث سياسة الخصوصية الخاصة بنا من وقت لآخر. سنعلمك بأي تغييرات عن طريق نشر سياسة الخصوصية الجديدة على هذه الصفحة.
سنخبرك عبر البريد الإلكتروني و/ أو بإشعار بارز في خدمتنا، قبل أن يصبح التغيير ساريًا وتحديث &quot;تاريخ الفعالية&quot; في أعلى سياسة الخصوصية هذه.
ننصحك بمراجعة سياسة الخصوصية هذه بشكل دوري لأية تغييرات. تسري التغييرات التي تطرأ على سياسة الخصوصية هذه عند نشرها على هذه الصفحة.', ''),
('30', '15', 'en', 'Changes To This Privacy Policy', '', 'We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.

We will let you know via email and/or a prominent notice on our Service, prior to the change becoming effective and update the &quot;effective date&quot; at the top of this Privacy Policy.

You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.', ''),
('31', '16', 'ar', 'اتصل بنا', NULL, 'إذا كان لديك أي أسئلة حول سياسة الخصوصية هذه، يرجى الاتصال بنا:', 'البريد الإلكتروني : [WebEmail]'),
('32', '16', 'en', 'Contact Us', NULL, 'If you have any questions about this Privacy Policy, please contact us:', 'Email : [WebEmail]');
COMMIT;
