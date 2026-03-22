function translatePage(lang){

  // NAVIGATION
  navHome.innerHTML = (lang=="es") ? "Inicio" :
                      (lang=="fr") ? "Accueil" :
                      (lang=="de") ? "Startseite" :
                      (lang=="zh") ? "首页" :
                      (lang=="ar") ? "الرئيسية" :
                      (lang=="ne") ? "घर" : "Home";

  navCourses.innerHTML = (lang=="es") ? "Cursos" :
                         (lang=="fr") ? "Cours" :
                         (lang=="de") ? "Kurse" :
                         (lang=="zh") ? "课程" :
                         (lang=="ar") ? "الدورات" :
                         (lang=="ne") ? "पाठ्यक्रम" : "Courses";

  navStudents.innerHTML = (lang=="es") ? "Estudiantes" :
                          (lang=="fr") ? "Étudiants" :
                          (lang=="de") ? "Studenten" :
                          (lang=="zh") ? "学生" :
                          (lang=="ar") ? "الطلاب" :
                          (lang=="ne") ? "विद्यार्थी" : "Students";

  navSupport.innerHTML = (lang=="es") ? "Soporte" :
                         (lang=="fr") ? "Support" :
                         (lang=="de") ? "Support" :
                         (lang=="zh") ? "支持" :
                         (lang=="ar") ? "الدعم" :
                         (lang=="ne") ? "समर्थन" : "Support";

  // SEARCH
  searchInput.placeholder = (lang=="es") ? "Buscar..." :
                            (lang=="fr") ? "Recherche..." :
                            (lang=="de") ? "Suchen..." :
                            (lang=="zh") ? "搜索..." :
                            (lang=="ar") ? "بحث..." :
                            (lang=="ne") ? "खोज्नुहोस्..." : "Search";

  searchBtn.innerHTML = (lang=="es") ? "Buscar" :
                         (lang=="fr") ? "Rechercher" :
                         (lang=="de") ? "Suchen" :
                         (lang=="zh") ? "搜索" :
                         (lang=="ar") ? "بحث" :
                         (lang=="ne") ? "खोज्नुहोस्" : "Search";

  // HERO
  welcome.innerHTML = (lang=="es") ? "Bienvenido a la página web WLV" :
                    (lang=="fr") ? "Bienvenue sur la page Web WLV" :
                    (lang=="de") ? "Willkommen auf der WLV Webseite" :
                    (lang=="zh") ? "欢迎来到 WLV 网站" :
                    (lang=="ar") ? "مرحبًا بكم في موقع WLV" :
                    (lang=="ne") ? "WLV वेब पृष्ठमा स्वागत छ" : "Welcome to WLV Web Page";

  subtitle.innerHTML = (lang=="es") ? "Sitio web profesional adaptable" :
                       (lang=="fr") ? "Site web professionnel responsive" :
                       (lang=="de") ? "Professionelle responsive Website" :
                       (lang=="zh") ? "专业响应式网站" :
                       (lang=="ar") ? "موقع احترافي متجاوب" :
                       (lang=="ne") ? "व्यावसायिक र प्रतिक्रियाशील वेबसाइट" : "Professional responsive website";

  // SERVICES
  servicesTitle.innerHTML = (lang=="es") ? "Nuestros Servicios" :
                           (lang=="fr") ? "Nos Services" :
                           (lang=="de") ? "Unsere Dienstleistungen" :
                           (lang=="zh") ? "我们的服务" :
                           (lang=="ar") ? "خدماتنا" :
                           (lang=="ne") ? "हाम्रा सेवाहरू" : "Our Services";

  courseTitle.innerHTML = (lang=="es") ? "Cursos" :
                          (lang=="fr") ? "Cours" :
                          (lang=="de") ? "Kurse" :
                          (lang=="zh") ? "课程" :
                          (lang=="ar") ? "الدورات" :
                          (lang=="ne") ? "पाठ्यक्रम" : "Courses";

  courseDesc.innerHTML = (lang=="es") ? "Cursos académicos de alta calidad" :
                          (lang=="fr") ? "Cours académiques de haute qualité" :
                          (lang=="de") ? "Hochwertige akademische Kurse" :
                          (lang=="zh") ? "高质量学术课程" :
                          (lang=="ar") ? "دورات أكاديمية عالية الجودة" :
                          (lang=="ne") ? "उच्च गुणस्तरको शैक्षिक पाठ्यक्रम।" : "High quality academic courses";

  studentTitle.innerHTML = (lang=="es") ? "Estudiantes" :
                           (lang=="fr") ? "Étudiants" :
                           (lang=="de") ? "Studenten" :
                           (lang=="zh") ? "学生" :
                           (lang=="ar") ? "الطلاب" :
                           (lang=="ne") ? "विद्यार्थी" : "Students";

  studentDesc.innerHTML = (lang=="es") ? "Panel y perfiles de estudiantes" :
                           (lang=="fr") ? "Tableau de bord et profils étudiants" :
                           (lang=="de") ? "Studenten Dashboard und Profile" :
                           (lang=="zh") ? "学生仪表板和资料" :
                           (lang=="ar") ? "لوحة تحكم الطلاب والملفات" :
                           (lang=="ne") ? "विद्यार्थी ड्यासबोर्ड र प्रोफाइल।" : "Student dashboard & profiles";

  libraryTitle.innerHTML = (lang=="es") ? "Biblioteca" :
                           (lang=="fr") ? "Bibliothèque" :
                           (lang=="de") ? "Bibliothek" :
                           (lang=="zh") ? "图书馆" :
                           (lang=="ar") ? "المكتبة" :
                           (lang=="ne") ? "पुस्तकालय" : "Library";

  libraryDesc.innerHTML = (lang=="es") ? "Recursos y materiales digitales" :
                           (lang=="fr") ? "Ressources et matériels numériques" :
                           (lang=="de") ? "Digitale Ressourcen und Materialien" :
                           (lang=="zh") ? "数字资源和材料" :
                           (lang=="ar") ? "الموارد والمواد الرقمية" :
                           (lang=="ne") ? "डिजिटल स्रोतहरू र सामग्री।" : "Digital resources & materials";

  supportTitle.innerHTML = (lang=="es") ? "Soporte" :
                           (lang=="fr") ? "Support" :
                           (lang=="de") ? "Support" :
                           (lang=="zh") ? "支持" :
                           (lang=="ar") ? "الدعم" :
                           (lang=="ne") ? "समर्थन" : "Support";

  supportDesc.innerHTML = (lang=="es") ? "Ayuda 24/7" :
                          (lang=="fr") ? "Assistance 24h/24" :
                          (lang=="de") ? "24/7 Hilfe und Unterstützung" :
                          (lang=="zh") ? "全天候帮助" :
                          (lang=="ar") ? "مساعدة على مدار الساعة" :
                          (lang=="ne") ? "२४/७ सहायता र सहयोग।" : "24/7 help & assistance";

  footerText.innerHTML = (lang=="es") ? "© 2026 Página Web WLV | Todos los derechos reservados" :
                          (lang=="fr") ? "© 2026 Page Web WLV | Tous droits réservés" :
                          (lang=="de") ? "© 2026 WLV Webseite | Alle Rechte vorbehalten" :
                          (lang=="zh") ? "© 2026 WLV 网站 | 版权所有" :
                          (lang=="ar") ? "© 2026 موقع WLV | جميع الحقوق محفوظة" :
                          (lang=="ne") ? "© 2026 WLV वेब पृष्ठ | सबै अधिकार सुरक्षित" : "© 2026 WLV Web Page | All Rights Reserved";
}