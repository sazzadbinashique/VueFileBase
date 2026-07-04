/* ==========================================================================
   Future Bridge Foundation — Site Data
   All bilingual content (English / Bangla) lives here so every page
   (index, project, videos, gallery) reads from one source of truth.
   ========================================================================== */

const SITE = {
  name: { en: "Future Bridge Foundation", bn: "ফিউচার ব্রিজ ফাউন্ডেশন" },
  tagline: {
    en: "Building bridges between hope and opportunity",
    bn: "আশা আর সুযোগের মাঝে সেতুবন্ধন গড়ি"
  },
  phone: "+880 1XXX-XXXXXX",
  email: "hello@futurebridgefoundation.org",
  address: { en: "Dhaka, Bangladesh", bn: "ঢাকা, বাংলাদেশ" }
};

/* ---------------------------------------------------------------------- */
/*  Projects                                                               */
/* ---------------------------------------------------------------------- */
const PROJECTS = [
  {
    id: "food-donation",
    icon: "utensils",
    color: "accent",
    cover: "https://placehold.co/1200x800/0E6B5C/F3F7F1?text=Food+Donation",
    gallery: [
      "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Meal+Packing",
      "https://placehold.co/700x500/E8A93B/10302B?text=Distribution+Day",
      "https://placehold.co/700x500/C43D3D/F3F7F1?text=Village+Visit",
      "https://placehold.co/700x500/10302B/F3F7F1?text=Ration+Bags",
      "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Volunteers",
      "https://placehold.co/700x500/E8A93B/10302B?text=Children+Fed"
    ],
    en: {
      title: "Food Donation",
      short: "Warm meals and monthly ration bags for families who can't make ends meet.",
      body: [
        "Every week our volunteers pack and deliver ration bags — rice, lentils, oil, and salt — to families across four districts who are one bad month away from going hungry.",
        "During Ramadan and flood season, we scale up to daily hot-meal distribution at community kitchens, feeding anyone who walks through the door, no questions asked.",
        "We work with local grocers and farmers first, which keeps costs low and puts money back into the same communities we serve."
      ],
      impact: [
        { value: "48,000+", label: "meals served" },
        { value: "3,200", label: "families supported monthly" },
        { value: "4", label: "districts reached" }
      ]
    },
    bn: {
      title: "খাদ্য সহায়তা",
      short: "যে পরিবারগুলোর মাসের শেষটা কষ্টে কাটে, তাদের জন্য গরম খাবার আর মাসিক রেশন ব্যাগ।",
      body: [
        "প্রতি সপ্তাহে আমাদের স্বেচ্ছাসেবকরা চাল, ডাল, তেল আর লবণ দিয়ে রেশন ব্যাগ প্যাক করে চারটি জেলার সেই পরিবারগুলোর কাছে পৌঁছে দেন, যাদের একটা খারাপ মাসই না খেয়ে থাকার কারণ হতে পারে।",
        "রমজান আর বন্যার মৌসুমে আমরা কমিউনিটি কিচেনে প্রতিদিনের গরম খাবার বিতরণ বাড়িয়ে দিই — যে কেউ এলেই খাবার পান, কোনো প্রশ্ন ছাড়াই।",
        "আমরা প্রথমে স্থানীয় মুদি দোকান আর কৃষকদের সাথে কাজ করি, এতে খরচ কম থাকে আর টাকাটাও একই কমিউনিটিতে ফিরে যায়, যাদের আমরা সেবা দিই।"
      ],
      impact: [
        { value: "৪৮,০০০+", label: "বেলা খাবার বিতরণ" },
        { value: "৩,২০০", label: "পরিবার মাসিক সহায়তাপ্রাপ্ত" },
        { value: "৪", label: "জেলায় কার্যক্রম" }
      ]
    }
  },
  {
    id: "education-support",
    icon: "book",
    color: "primary",
    cover: "https://placehold.co/1200x800/E8A93B/10302B?text=Education+Support",
    gallery: [
      "https://placehold.co/700x500/E8A93B/10302B?text=Classroom",
      "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Book+Drive",
      "https://placehold.co/700x500/C43D3D/F3F7F1?text=Scholarship+Day",
      "https://placehold.co/700x500/10302B/F3F7F1?text=Reading+Corner",
      "https://placehold.co/700x500/E8A93B/10302B?text=Tutoring",
      "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Graduation"
    ],
    en: {
      title: "Education Support",
      short: "Scholarships, school supplies, and free tutoring so no child drops out over money.",
      body: [
        "We cover tuition, books, and uniforms for children whose families would otherwise pull them out of school during hard times.",
        "Our after-school tutoring circles, run by university-student volunteers, help kids catch up in math and Bangla when classrooms are overcrowded.",
        "Every year we hand out fresh scholarships to top-performing students from our program so they can continue into secondary school and beyond."
      ],
      impact: [
        { value: "1,150", label: "students sponsored" },
        { value: "62", label: "scholarships this year" },
        { value: "18", label: "partner schools" }
      ]
    },
    bn: {
      title: "শিক্ষা সহায়তা",
      short: "বৃত্তি, স্কুল উপকরণ আর বিনামূল্যে টিউশন — যেন টাকার অভাবে কোনো শিশুর পড়াশোনা না থামে।",
      body: [
        "যে পরিবারগুলো কঠিন সময়ে সন্তানকে স্কুল থেকে সরিয়ে নিতে বাধ্য হতো, তাদের সন্তানদের বেতন, বই আর ইউনিফর্মের খরচ আমরা বহন করি।",
        "বিশ্ববিদ্যালয় ছাত্র-স্বেচ্ছাসেবকদের পরিচালিত আমাদের স্কুল-পরবর্তী টিউশন সার্কেল, ভিড়ে ঠাসা ক্লাসরুমে পিছিয়ে পড়া শিশুদের গণিত আর বাংলায় এগিয়ে আনতে সাহায্য করে।",
        "প্রতি বছর আমাদের প্রোগ্রামের সেরা শিক্ষার্থীদের নতুন বৃত্তি দেওয়া হয়, যাতে তারা মাধ্যমিক ও তার পরেও পড়াশোনা চালিয়ে যেতে পারে।"
      ],
      impact: [
        { value: "১,১৫০", label: "শিক্ষার্থী পৃষ্ঠপোষকতাপ্রাপ্ত" },
        { value: "৬২", label: "এই বছরের বৃত্তি" },
        { value: "১৮", label: "অংশীদার স্কুল" }
      ]
    }
  },
  {
    id: "healthcare-assistance",
    icon: "heart-pulse",
    color: "accent2",
    cover: "https://placehold.co/1200x800/C43D3D/F3F7F1?text=Healthcare+Assistance",
    gallery: [
      "https://placehold.co/700x500/C43D3D/F3F7F1?text=Free+Camp",
      "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Checkup",
      "https://placehold.co/700x500/E8A93B/10302B?text=Medicine+Drive",
      "https://placehold.co/700x500/10302B/F3F7F1?text=Mother+%26+Child",
      "https://placehold.co/700x500/C43D3D/F3F7F1?text=Vaccination",
      "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Ambulance+Fund"
    ],
    en: {
      title: "Healthcare Assistance",
      short: "Free medical camps, medicine, and emergency transport for families with nowhere else to turn.",
      body: [
        "Our monthly free health camps bring doctors, basic diagnostics, and medicine directly to villages that are hours from the nearest clinic.",
        "We run a small emergency fund that covers ambulance costs and urgent medicine for families who would otherwise skip treatment entirely.",
        "Maternal and child health is a priority — regular checkups, nutrition support, and safe-delivery guidance for expecting mothers."
      ],
      impact: [
        { value: "9,400+", label: "patients treated" },
        { value: "36", label: "free health camps" },
        { value: "210", label: "emergency cases funded" }
      ]
    },
    bn: {
      title: "স্বাস্থ্যসেবা",
      short: "যাদের যাওয়ার আর কোনো জায়গা নেই, তাদের জন্য বিনামূল্যে চিকিৎসা ক্যাম্প, ওষুধ আর জরুরি পরিবহন।",
      body: [
        "আমাদের মাসিক বিনামূল্যে স্বাস্থ্য ক্যাম্প নিকটতম ক্লিনিক থেকে ঘণ্টাখানেক দূরের গ্রামগুলোতে সরাসরি ডাক্তার, প্রাথমিক পরীক্ষা আর ওষুধ পৌঁছে দেয়।",
        "যে পরিবারগুলো চিকিৎসা এড়িয়ে যেত, তাদের জন্য আমরা একটি ছোট জরুরি তহবিল পরিচালনা করি, যা অ্যাম্বুলেন্স খরচ আর জরুরি ওষুধ বহন করে।",
        "মা ও শিশু স্বাস্থ্য আমাদের অগ্রাধিকার — গর্ভবতী মায়েদের জন্য নিয়মিত চেকআপ, পুষ্টি সহায়তা আর নিরাপদ প্রসবের পরামর্শ।"
      ],
      impact: [
        { value: "৯,৪০০+", label: "রোগী চিকিৎসাপ্রাপ্ত" },
        { value: "৩৬", label: "বিনামূল্যে স্বাস্থ্য ক্যাম্প" },
        { value: "২১০", label: "জরুরি সহায়তা প্রদান" }
      ]
    }
  },
  {
    id: "water-sanitation",
    icon: "droplet",
    color: "primary",
    cover: "https://placehold.co/1200x800/10302B/F3F7F1?text=Water+%26+Sanitation",
    gallery: [
      "https://placehold.co/700x500/10302B/F3F7F1?text=Tubewell",
      "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Latrine+Build",
      "https://placehold.co/700x500/E8A93B/10302B?text=Hygiene+Training",
      "https://placehold.co/700x500/C43D3D/F3F7F1?text=Clean+Water+Day",
      "https://placehold.co/700x500/10302B/F3F7F1?text=School+Wash+Block",
      "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Community+Pump"
    ],
    en: {
      title: "Water & Sanitation",
      short: "Safe tube-wells, latrines, and hygiene training for villages battling contaminated water.",
      body: [
        "We install and maintain arsenic-tested tube-wells in villages where the nearest safe water source used to be an hour's walk away.",
        "Our sanitation teams build low-cost latrines for households and schools, cutting waterborne illness dramatically in the areas we've covered.",
        "Hygiene workshops for mothers and schoolchildren turn a one-time installation into a lasting habit."
      ],
      impact: [
        { value: "76", label: "tube-wells installed" },
        { value: "1,900", label: "latrines built" },
        { value: "12,000+", label: "people with clean water access" }
      ]
    },
    bn: {
      title: "পানি ও স্যানিটেশন",
      short: "দূষিত পানির সাথে লড়াই করা গ্রামগুলোর জন্য নিরাপদ নলকূপ, ল্যাট্রিন আর স্বাস্থ্যবিধি প্রশিক্ষণ।",
      body: [
        "যেসব গ্রামে নিকটতম নিরাপদ পানির উৎস এক ঘণ্টার হাঁটাপথ ছিল, সেখানে আমরা আর্সেনিক-পরীক্ষিত নলকূপ স্থাপন ও রক্ষণাবেক্ষণ করি।",
        "আমাদের স্যানিটেশন দল বাড়ি আর স্কুলের জন্য কম খরচে ল্যাট্রিন তৈরি করে, যা আমাদের কার্যক্রম এলাকায় পানিবাহিত রোগ উল্লেখযোগ্যভাবে কমিয়ে দিয়েছে।",
        "মা ও স্কুলশিক্ষার্থীদের জন্য স্বাস্থ্যবিধি কর্মশালা একবারের স্থাপনাকে দীর্ঘস্থায়ী অভ্যাসে পরিণত করে।"
      ],
      impact: [
        { value: "৭৬", label: "নলকূপ স্থাপিত" },
        { value: "১,৯০০", label: "ল্যাট্রিন নির্মিত" },
        { value: "১২,০০০+", label: "মানুষ নিরাপদ পানির সুবিধাপ্রাপ্ত" }
      ]
    }
  }
];

/* ---------------------------------------------------------------------- */
/*  Videos                                                                 */
/* ---------------------------------------------------------------------- */
const VIDEOS = [
  {
    id: "v1", youtubeId: "dQw4w9WgXcQ", projectId: "food-donation",
    en: { title: "A Day at the Ration Drive", desc: "Follow our volunteers as they pack and deliver monthly ration bags." },
    bn: { title: "রেশন বিতরণের একদিন", desc: "স্বেচ্ছাসেবকরা কীভাবে মাসিক রেশন ব্যাগ প্যাক ও বিতরণ করেন।" }
  },
  {
    id: "v2", youtubeId: "dQw4w9WgXcQ", projectId: "education-support",
    en: { title: "Inside Our Tutoring Circles", desc: "University volunteers help students catch up after school." },
    bn: { title: "টিউশন সার্কেলের ভিতরে", desc: "বিশ্ববিদ্যালয় স্বেচ্ছাসেবকরা কীভাবে শিক্ষার্থীদের সাহায্য করেন।" }
  },
  {
    id: "v3", youtubeId: "dQw4w9WgXcQ", projectId: "healthcare-assistance",
    en: { title: "Free Health Camp, Rangpur", desc: "Doctors and medicine reach a village hours from the nearest clinic." },
    bn: { title: "বিনামূল্যে স্বাস্থ্য ক্যাম্প, রংপুর", desc: "নিকটতম ক্লিনিক থেকে ঘণ্টাখানেক দূরের গ্রামে ডাক্তার ও ওষুধ।" }
  },
  {
    id: "v4", youtubeId: "dQw4w9WgXcQ", projectId: "water-sanitation",
    en: { title: "Clean Water for Char Ishwardi", desc: "Installing a new arsenic-tested tube-well in a riverside village." },
    bn: { title: "চর ঈশ্বরদীর জন্য নিরাপদ পানি", desc: "নদীতীরের এক গ্রামে নতুন আর্সেনিক-পরীক্ষিত নলকূপ স্থাপন।" }
  },
  {
    id: "v5", youtubeId: "dQw4w9WgXcQ", projectId: "education-support",
    en: { title: "Scholarship Day 2025", desc: "62 students receive scholarships to continue their education." },
    bn: { title: "বৃত্তি দিবস ২০২৫", desc: "৬২ জন শিক্ষার্থী পড়াশোনা চালিয়ে যেতে বৃত্তি পেলেন।" }
  },
  {
    id: "v6", youtubeId: "dQw4w9WgXcQ", projectId: "food-donation",
    en: { title: "Ramadan Iftar Kitchens", desc: "Community kitchens serving hot iftar to anyone who needs it." },
    bn: { title: "রমজানের ইফতার কিচেন", desc: "যে কারো জন্য গরম ইফতার পরিবেশনকারী কমিউনিটি কিচেন।" }
  }
];

/* ---------------------------------------------------------------------- */
/*  Gallery                                                                */
/* ---------------------------------------------------------------------- */
const GALLERY = [
  { img: "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Ration+Day", projectId: "food-donation", en: "Ration distribution day", bn: "রেশন বিতরণের দিন" },
  { img: "https://placehold.co/700x500/E8A93B/10302B?text=Classroom", projectId: "education-support", en: "A sponsored classroom", bn: "পৃষ্ঠপোষকতাপ্রাপ্ত ক্লাসরুম" },
  { img: "https://placehold.co/700x500/C43D3D/F3F7F1?text=Health+Camp", projectId: "healthcare-assistance", en: "Free health camp", bn: "বিনামূল্যে স্বাস্থ্য ক্যাম্প" },
  { img: "https://placehold.co/700x500/10302B/F3F7F1?text=Tube-well", projectId: "water-sanitation", en: "New tube-well installation", bn: "নতুন নলকূপ স্থাপন" },
  { img: "https://placehold.co/700x500/E8A93B/10302B?text=Book+Drive", projectId: "education-support", en: "Annual book drive", bn: "বার্ষিক বই সংগ্রহ" },
  { img: "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Volunteers", projectId: "food-donation", en: "Our volunteer team", bn: "আমাদের স্বেচ্ছাসেবক দল" },
  { img: "https://placehold.co/700x500/C43D3D/F3F7F1?text=Vaccination", projectId: "healthcare-assistance", en: "Child vaccination drive", bn: "শিশু টিকাদান কর্মসূচি" },
  { img: "https://placehold.co/700x500/10302B/F3F7F1?text=Hygiene+Talk", projectId: "water-sanitation", en: "Hygiene workshop", bn: "স্বাস্থ্যবিধি কর্মশালা" },
  { img: "https://placehold.co/700x500/E8A93B/10302B?text=Scholarship", projectId: "education-support", en: "Scholarship award ceremony", bn: "বৃত্তি প্রদান অনুষ্ঠান" },
  { img: "https://placehold.co/700x500/0E6B5C/F3F7F1?text=Iftar", projectId: "food-donation", en: "Community iftar", bn: "কমিউনিটি ইফতার" },
  { img: "https://placehold.co/700x500/C43D3D/F3F7F1?text=Mother+Care", projectId: "healthcare-assistance", en: "Maternal health checkup", bn: "মাতৃস্বাস্থ্য পরীক্ষা" },
  { img: "https://placehold.co/700x500/10302B/F3F7F1?text=Latrine", projectId: "water-sanitation", en: "Latrine construction", bn: "ল্যাট্রিন নির্মাণ" }
];
