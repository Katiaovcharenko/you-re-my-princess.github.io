PGDMP  "                    }            salon_beauty    17.2    17.2 Z    d           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            e           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            f           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            g           1262    16903    salon_beauty    DATABASE     �   CREATE DATABASE salon_beauty WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Russian_Russia.1251';
    DROP DATABASE salon_beauty;
                     postgres    false            �            1259    16904    appointments    TABLE     �  CREATE TABLE public.appointments (
    id integer NOT NULL,
    client_id integer,
    service_id integer,
    specialist_id integer,
    date date NOT NULL,
    "time" time without time zone NOT NULL,
    status character varying(50) NOT NULL,
    CONSTRAINT appointments_status_check CHECK (((status)::text = ANY (ARRAY[('Запланировано'::character varying)::text, ('Выполнено'::character varying)::text, ('Отменено'::character varying)::text])))
);
     DROP TABLE public.appointments;
       public         heap r       postgres    false            �            1259    16908    appointments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.appointments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.appointments_id_seq;
       public               postgres    false    217            h           0    0    appointments_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.appointments_id_seq OWNED BY public.appointments.id;
          public               postgres    false    218            �            1259    16909    book_appointments    TABLE       CREATE TABLE public.book_appointments (
    date date NOT NULL,
    "time" time without time zone NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone character varying(255) NOT NULL,
    service integer,
    id integer NOT NULL
);
 %   DROP TABLE public.book_appointments;
       public         heap r       postgres    false            �            1259    16914    book_appointments_seq    SEQUENCE     ~   CREATE SEQUENCE public.book_appointments_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE public.book_appointments_seq;
       public               postgres    false    219            i           0    0    book_appointments_seq    SEQUENCE OWNED BY     R   ALTER SEQUENCE public.book_appointments_seq OWNED BY public.book_appointments.id;
          public               postgres    false    220            �            1259    16915 
   categories    TABLE     g   CREATE TABLE public.categories (
    id integer NOT NULL,
    value character varying(255) NOT NULL
);
    DROP TABLE public.categories;
       public         heap r       postgres    false            �            1259    16918    categories_id_seq    SEQUENCE     �   CREATE SEQUENCE public.categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.categories_id_seq;
       public               postgres    false    221            j           0    0    categories_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;
          public               postgres    false    222            �            1259    16919    clients    TABLE     �   CREATE TABLE public.clients (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    contact_info character varying(255) NOT NULL,
    preferences text
);
    DROP TABLE public.clients;
       public         heap r       postgres    false            �            1259    16924    clients_id_seq    SEQUENCE     �   CREATE SEQUENCE public.clients_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.clients_id_seq;
       public               postgres    false    223            k           0    0    clients_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.clients_id_seq OWNED BY public.clients.id;
          public               postgres    false    224            �            1259    16925 
   promotions    TABLE     �   CREATE TABLE public.promotions (
    promo_id integer NOT NULL,
    pname character varying(255) NOT NULL,
    description character varying(255) NOT NULL
);
    DROP TABLE public.promotions;
       public         heap r       postgres    false            �            1259    16930    promotions_id_seq    SEQUENCE     �   CREATE SEQUENCE public.promotions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 2147483647
    CACHE 1;
 (   DROP SEQUENCE public.promotions_id_seq;
       public               postgres    false    225            l           0    0    promotions_id_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE public.promotions_id_seq OWNED BY public.promotions.promo_id;
          public               postgres    false    226            �            1259    16931    reviews    TABLE     &  CREATE TABLE public.reviews (
    id integer NOT NULL,
    client_id integer,
    specialist_id integer,
    rating integer NOT NULL,
    comment text,
    date timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT reviews_rating_check CHECK (((rating >= 1) AND (rating <= 5)))
);
    DROP TABLE public.reviews;
       public         heap r       postgres    false            �            1259    16938    reviews_id_seq    SEQUENCE     �   CREATE SEQUENCE public.reviews_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.reviews_id_seq;
       public               postgres    false    227            m           0    0    reviews_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.reviews_id_seq OWNED BY public.reviews.id;
          public               postgres    false    228            �            1259    16939    services    TABLE     �  CREATE TABLE public.services (
    service_id integer NOT NULL,
    sname character varying(255) NOT NULL,
    description text,
    price numeric(10,2) NOT NULL,
    duration integer NOT NULL,
    category_id integer,
    image character varying(255),
    CONSTRAINT services_duration_check CHECK ((duration > 0)),
    CONSTRAINT services_price_check CHECK ((price >= (0)::numeric))
);
    DROP TABLE public.services;
       public         heap r       postgres    false            �            1259    16946    services_id_seq    SEQUENCE     �   CREATE SEQUENCE public.services_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.services_id_seq;
       public               postgres    false    229            n           0    0    services_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.services_id_seq OWNED BY public.services.service_id;
          public               postgres    false    230            �            1259    16947    specialists    TABLE     �  CREATE TABLE public.specialists (
    id integer NOT NULL,
    value character varying(255) NOT NULL,
    speciality_id integer,
    experience integer NOT NULL,
    rating numeric(10,2),
    image character varying(255),
    CONSTRAINT specialists_experience_check CHECK ((experience >= 0)),
    CONSTRAINT specialists_rating_check CHECK (((rating >= (0)::numeric) AND (rating <= (5)::numeric)))
);
    DROP TABLE public.specialists;
       public         heap r       postgres    false            �            1259    16954    specialists_id_seq    SEQUENCE     �   CREATE SEQUENCE public.specialists_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.specialists_id_seq;
       public               postgres    false    231            o           0    0    specialists_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.specialists_id_seq OWNED BY public.specialists.id;
          public               postgres    false    232            �            1259    16955    specialities    TABLE     i   CREATE TABLE public.specialities (
    id integer NOT NULL,
    value character varying(255) NOT NULL
);
     DROP TABLE public.specialities;
       public         heap r       postgres    false            �            1259    16958    specialities_id_seq    SEQUENCE     �   CREATE SEQUENCE public.specialities_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.specialities_id_seq;
       public               postgres    false    233            p           0    0    specialities_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.specialities_id_seq OWNED BY public.specialities.id;
          public               postgres    false    234            �            1259    16959    users    TABLE     �  CREATE TABLE public.users (
    firstname character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(50) DEFAULT 'Клиент'::character varying NOT NULL,
    id integer NOT NULL,
    secondname character varying(255),
    fathername character varying(255),
    profile_picture character varying(255) DEFAULT 'empty.jpg'::character varying
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    16965    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    235            q           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    236            �           2604    16966    appointments id    DEFAULT     r   ALTER TABLE ONLY public.appointments ALTER COLUMN id SET DEFAULT nextval('public.appointments_id_seq'::regclass);
 >   ALTER TABLE public.appointments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    218    217            �           2604    16967    book_appointments id    DEFAULT     y   ALTER TABLE ONLY public.book_appointments ALTER COLUMN id SET DEFAULT nextval('public.book_appointments_seq'::regclass);
 C   ALTER TABLE public.book_appointments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    220    219            �           2604    16968    categories id    DEFAULT     n   ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);
 <   ALTER TABLE public.categories ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    222    221            �           2604    16969 
   clients id    DEFAULT     h   ALTER TABLE ONLY public.clients ALTER COLUMN id SET DEFAULT nextval('public.clients_id_seq'::regclass);
 9   ALTER TABLE public.clients ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    224    223            �           2604    16970    promotions promo_id    DEFAULT     t   ALTER TABLE ONLY public.promotions ALTER COLUMN promo_id SET DEFAULT nextval('public.promotions_id_seq'::regclass);
 B   ALTER TABLE public.promotions ALTER COLUMN promo_id DROP DEFAULT;
       public               postgres    false    226    225            �           2604    16971 
   reviews id    DEFAULT     h   ALTER TABLE ONLY public.reviews ALTER COLUMN id SET DEFAULT nextval('public.reviews_id_seq'::regclass);
 9   ALTER TABLE public.reviews ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    228    227            �           2604    16972    services service_id    DEFAULT     r   ALTER TABLE ONLY public.services ALTER COLUMN service_id SET DEFAULT nextval('public.services_id_seq'::regclass);
 B   ALTER TABLE public.services ALTER COLUMN service_id DROP DEFAULT;
       public               postgres    false    230    229            �           2604    16973    specialists id    DEFAULT     p   ALTER TABLE ONLY public.specialists ALTER COLUMN id SET DEFAULT nextval('public.specialists_id_seq'::regclass);
 =   ALTER TABLE public.specialists ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    232    231            �           2604    16974    specialities id    DEFAULT     r   ALTER TABLE ONLY public.specialities ALTER COLUMN id SET DEFAULT nextval('public.specialities_id_seq'::regclass);
 >   ALTER TABLE public.specialities ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    234    233            �           2604    16975    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    236    235            N          0    16904    appointments 
   TABLE DATA           f   COPY public.appointments (id, client_id, service_id, specialist_id, date, "time", status) FROM stdin;
    public               postgres    false    217   p       P          0    16909    book_appointments 
   TABLE DATA           Z   COPY public.book_appointments (date, "time", name, email, phone, service, id) FROM stdin;
    public               postgres    false    219   4p       R          0    16915 
   categories 
   TABLE DATA           /   COPY public.categories (id, value) FROM stdin;
    public               postgres    false    221   q       T          0    16919    clients 
   TABLE DATA           F   COPY public.clients (id, name, contact_info, preferences) FROM stdin;
    public               postgres    false    223   �q       V          0    16925 
   promotions 
   TABLE DATA           B   COPY public.promotions (promo_id, pname, description) FROM stdin;
    public               postgres    false    225   1r       X          0    16931    reviews 
   TABLE DATA           V   COPY public.reviews (id, client_id, specialist_id, rating, comment, date) FROM stdin;
    public               postgres    false    227   �r       Z          0    16939    services 
   TABLE DATA           g   COPY public.services (service_id, sname, description, price, duration, category_id, image) FROM stdin;
    public               postgres    false    229   �r       \          0    16947    specialists 
   TABLE DATA           Z   COPY public.specialists (id, value, speciality_id, experience, rating, image) FROM stdin;
    public               postgres    false    231   �}       ^          0    16955    specialities 
   TABLE DATA           1   COPY public.specialities (id, value) FROM stdin;
    public               postgres    false    233   �}       `          0    16959    users 
   TABLE DATA           u   COPY public.users (firstname, email, phone, password, role, id, secondname, fathername, profile_picture) FROM stdin;
    public               postgres    false    235   O~       r           0    0    appointments_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.appointments_id_seq', 11, true);
          public               postgres    false    218            s           0    0    book_appointments_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('public.book_appointments_seq', 12, true);
          public               postgres    false    220            t           0    0    categories_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.categories_id_seq', 4, true);
          public               postgres    false    222            u           0    0    clients_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.clients_id_seq', 2, true);
          public               postgres    false    224            v           0    0    promotions_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.promotions_id_seq', 2, true);
          public               postgres    false    226            w           0    0    reviews_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.reviews_id_seq', 2, true);
          public               postgres    false    228            x           0    0    services_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.services_id_seq', 7, true);
          public               postgres    false    230            y           0    0    specialists_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.specialists_id_seq', 1, false);
          public               postgres    false    232            z           0    0    specialities_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.specialities_id_seq', 7, true);
          public               postgres    false    234            {           0    0    users_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.users_id_seq', 11, true);
          public               postgres    false    236            �           2606    16977    appointments appointments_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.appointments DROP CONSTRAINT appointments_pkey;
       public                 postgres    false    217            �           2606    16979 (   book_appointments book_appointments_pkey 
   CONSTRAINT     f   ALTER TABLE ONLY public.book_appointments
    ADD CONSTRAINT book_appointments_pkey PRIMARY KEY (id);
 R   ALTER TABLE ONLY public.book_appointments DROP CONSTRAINT book_appointments_pkey;
       public                 postgres    false    219            �           2606    16981    categories categories_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.categories DROP CONSTRAINT categories_pkey;
       public                 postgres    false    221            �           2606    16983    categories categories_value_key 
   CONSTRAINT     [   ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_value_key UNIQUE (value);
 I   ALTER TABLE ONLY public.categories DROP CONSTRAINT categories_value_key;
       public                 postgres    false    221            �           2606    16985    clients clients_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.clients DROP CONSTRAINT clients_pkey;
       public                 postgres    false    223            �           2606    16987    promotions promotions_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY public.promotions
    ADD CONSTRAINT promotions_pkey PRIMARY KEY (promo_id);
 D   ALTER TABLE ONLY public.promotions DROP CONSTRAINT promotions_pkey;
       public                 postgres    false    225            �           2606    16989    reviews reviews_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_pkey;
       public                 postgres    false    227            �           2606    16991    services services_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_pkey PRIMARY KEY (service_id);
 @   ALTER TABLE ONLY public.services DROP CONSTRAINT services_pkey;
       public                 postgres    false    229            �           2606    16993    specialists specialists_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.specialists
    ADD CONSTRAINT specialists_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.specialists DROP CONSTRAINT specialists_pkey;
       public                 postgres    false    231            �           2606    16995    specialities specialities_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.specialities
    ADD CONSTRAINT specialities_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.specialities DROP CONSTRAINT specialities_pkey;
       public                 postgres    false    233            �           2606    16997 #   specialities specialities_value_key 
   CONSTRAINT     _   ALTER TABLE ONLY public.specialities
    ADD CONSTRAINT specialities_value_key UNIQUE (value);
 M   ALTER TABLE ONLY public.specialities DROP CONSTRAINT specialities_value_key;
       public                 postgres    false    233            �           2606    16999    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    235            �           2606    17000 (   appointments appointments_client_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_client_id_fkey FOREIGN KEY (client_id) REFERENCES public.clients(id) ON DELETE CASCADE;
 R   ALTER TABLE ONLY public.appointments DROP CONSTRAINT appointments_client_id_fkey;
       public               postgres    false    4768    217    223            �           2606    17005 )   appointments appointments_service_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_service_id_fkey FOREIGN KEY (service_id) REFERENCES public.services(service_id) ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.appointments DROP CONSTRAINT appointments_service_id_fkey;
       public               postgres    false    217    4774    229            �           2606    17010 ,   appointments appointments_specialist_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_specialist_id_fkey FOREIGN KEY (specialist_id) REFERENCES public.specialists(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.appointments DROP CONSTRAINT appointments_specialist_id_fkey;
       public               postgres    false    217    4776    231            �           2606    17015 #   appointments fk_appointments_client    FK CONSTRAINT     �   ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT fk_appointments_client FOREIGN KEY (client_id) REFERENCES public.clients(id) ON DELETE CASCADE;
 M   ALTER TABLE ONLY public.appointments DROP CONSTRAINT fk_appointments_client;
       public               postgres    false    4768    223    217            �           2606    17020 $   appointments fk_appointments_service    FK CONSTRAINT     �   ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT fk_appointments_service FOREIGN KEY (service_id) REFERENCES public.services(service_id) ON DELETE CASCADE;
 N   ALTER TABLE ONLY public.appointments DROP CONSTRAINT fk_appointments_service;
       public               postgres    false    217    4774    229            �           2606    17025 '   appointments fk_appointments_specialist    FK CONSTRAINT     �   ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT fk_appointments_specialist FOREIGN KEY (specialist_id) REFERENCES public.specialists(id) ON DELETE CASCADE;
 Q   ALTER TABLE ONLY public.appointments DROP CONSTRAINT fk_appointments_specialist;
       public               postgres    false    217    4776    231            �           2606    17030    reviews fk_reviews_client    FK CONSTRAINT     �   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT fk_reviews_client FOREIGN KEY (client_id) REFERENCES public.clients(id) ON DELETE CASCADE;
 C   ALTER TABLE ONLY public.reviews DROP CONSTRAINT fk_reviews_client;
       public               postgres    false    227    4768    223            �           2606    17035    reviews fk_reviews_specialist    FK CONSTRAINT     �   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT fk_reviews_specialist FOREIGN KEY (specialist_id) REFERENCES public.specialists(id) ON DELETE CASCADE;
 G   ALTER TABLE ONLY public.reviews DROP CONSTRAINT fk_reviews_specialist;
       public               postgres    false    227    4776    231            �           2606    17040    services fk_services_category    FK CONSTRAINT     �   ALTER TABLE ONLY public.services
    ADD CONSTRAINT fk_services_category FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;
 G   ALTER TABLE ONLY public.services DROP CONSTRAINT fk_services_category;
       public               postgres    false    229    4764    221            �           2606    17045 %   specialists fk_specialists_speciality    FK CONSTRAINT     �   ALTER TABLE ONLY public.specialists
    ADD CONSTRAINT fk_specialists_speciality FOREIGN KEY (speciality_id) REFERENCES public.specialities(id) ON DELETE CASCADE;
 O   ALTER TABLE ONLY public.specialists DROP CONSTRAINT fk_specialists_speciality;
       public               postgres    false    233    231    4778            �           2606    17050    reviews reviews_client_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_client_id_fkey FOREIGN KEY (client_id) REFERENCES public.clients(id) ON DELETE CASCADE;
 H   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_client_id_fkey;
       public               postgres    false    4768    223    227            �           2606    17055 "   reviews reviews_specialist_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_specialist_id_fkey FOREIGN KEY (specialist_id) REFERENCES public.specialists(id) ON DELETE CASCADE;
 L   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_specialist_id_fkey;
       public               postgres    false    4776    231    227            �           2606    17060 "   services services_category_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_category_id_fkey FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;
 L   ALTER TABLE ONLY public.services DROP CONSTRAINT services_category_id_fkey;
       public               postgres    false    4764    229    221            �           2606    17065 *   specialists specialists_speciality_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.specialists
    ADD CONSTRAINT specialists_speciality_id_fkey FOREIGN KEY (speciality_id) REFERENCES public.specialities(id) ON DELETE CASCADE;
 T   ALTER TABLE ONLY public.specialists DROP CONSTRAINT specialists_speciality_id_fkey;
       public               postgres    false    231    4778    233            N      x������ � �      P   �   x���=
�@�z�*�03�M�[� 6!�H����=�z+ X(�g���IR��Wo�I �3�!�?�������+��z�*��0���2l60I,Zmk�@@��������1�=Á"�\42�n+��_����ț��T�U/QJ�c��@âhx,��ԩ��Ww�~v���RK6���i����D^h      R   s   x�E���@D��*�$>�c�������V�\o;b !{ogF�M�(��XE'j�q�95�(\bd�m����yӘc�r�-+�Z�b�ĕ&\u�Y~=��}xRxX�1��{X�      T   �   x��=�@���SloB� �'�4CA"b,�%�P�Z�o@�5D#^aލ2�L�7�|��4R�*<�^�����*\G,�������A7�����Zj�&��2�&��+{r6�����qr��cf��������d��.��E��X'���EE]e      V   ]   x�3⼰���{�x煽�/�T���b����.l��H�T�42UU��xaׅ@1 � �v_��liT S{.����_؇b��=�\1z\\\ !�C�      X      x������ � �      Z   �
  x��Y�r��]�_��]�aI���d3�IVYg�K�ʫj��g�%EZ�H#���dʙ$��E�DH H �p����9� �JZX&�������^��'�����L������H������n�
y��Eu���twv��;�n��/z�߼�y�빏2t\}p��z�_����W�jT�1���~������~��U�s��9ƞ�Mu,_F.uwj�H�#�.�w!,�WbM�R+m����U<��"|�2��`Yyc�j�"p�����a��������h��~�2�L���:����Au&���OH�#��
�{O����PM�݃	l[���U��d���_���U���0��{��\*�r}�ފE�$�:A5��n��	���>ʮ�M�W1�  )�^��؍̗�1��,T]�Dʨ��Y5�iZBw�p��J��ꪺ�`�Hf��0�F\Ȝy(Q���������{37�y�j@�b�B���P�M,_G��k�:܏8_e�5
��bI�J����q�0�[�����Nde��2@�;�1�z�R�i
J	��%�a�V8�v��n�M�k�s��Xc��ov�'>a��Y�)MX�#�yI2�P+�PYOj�� F���V�$�K�ȐL�,�u%���vO�Ir�&XA�mA�GȮ��7���Ȳ93�ň��+�2���}�m���wL�A"e\�\���ҡ�b�|��{Ip7F�.�����a���r�:�� 00�Ǻ]rf����H������i��浚JPj*z�ȘJ+��]�۩�?�X��Xc��clB t�Bd+�X�h1�iT8��դb	��V���\쌕���g%�Z0�Rz���6�Y,�dfRՁ�Ϲ,j6c
|��*u��ⳕ�D$2�c�N�Q�����QwQ⋆��� "�^�{��;��1pA﯍I_�e�KXa�i1 �q	S%�Nh��_7��&��R�;�����5�y^vB�4�[�4��T
�|$S�䫨� L��ā.B�or�M��9�(f-z���.�9,6�rϙ�3��JtN�@����#˫�U��!q`��)o���6���S �#Έ�H�&�v�.����)�"c.��ioH��s�:fMc��T^�d_=Ɋ���짜_�]�V�?�4�[�Z��κˑ��3a�6;c�Rk�V��~���/k[-U�hm"���|�6�k���"������J�b(�#�9��F-x��UQ�	u�z�1� B�ܢZ96#c���'�"�?��Z�R@���X�5�Z�~��bv��	�M�j��d}�D�ZBA��8=�Xc�a��Ko�fZi�g�k��!��:��T*�FOQe(�8��DE��-5CX��5N���XIQST��f�{��$[^<Ĳ��"�j������u_�L���n�G֥��*��L���̄u�o=�HJ'�J��[-���)+,���%��4]�h���l/�I�K,�Ob��f���kϲ�Y>S(���M�[J3=������V8�~��Cl��JX��oH��)�״B-���D�&dѳt �w������<1���!=�}M[��� t��Q�+D�E�1u*j��k�P��V2a��Tj���vB�;ѫ��o����c���M#��!��@z��?�O 8��3����|�8�`���J����e����y)�����)�jUŷ�m��Fjw��Qu�����;mk�5�4m�(��v�G栗���L\f��B"�)�������d*3�=�z����\ǽ�^��<O)䖤LCv��Bmw����Q��"���E?ւc��n�E��>�+�
�\��㳪���ei�w��=]�1+����w�fjcLf/T���jd9#yjn�X�e��ܙ,�u`$�AE͙�dM�Y(��ML��GM<�ܩ��ʞl@&��T��4]m���X�}��ʰ~�	��#����5��-n#^�$��\����86��aUN�Їz�Ae0�kG�Ԛ0��Ą�һ�m����\��xpg�K���J���q�o��OI�Ȇ���α)�Ȅ�ɔԗll>�v�n�v�-B�u�A��Ǟ�P<akS�3o�A�8�' ?������<$�2d��F��a� ��� ?#����^44��J�-�j*���ێ�+�zat�ޮ��X�DO�)|Pg/��-2�h�G�)zB��j�m�}`t;γ����Y9������h�:F̣�B&P>Z�Z홪>�Y[oܪ+�&�SXC�)G�]Q԰��zU��2�\�q!q��Y�rV���:��˻�O%R?GsJH@ke���j-���G��V.D���*�C�]���f����Z��QUЬ�@��C� �|nw�����sM�6lJ������Y[���.s�m]Z���kX!Of�]�*X�M/t3�{������,>���r[��W��w��Dȇ�j�rP��n�%���j�!g}yv�"ڥ��*�]�78<�R��C��H��4��<��O������dfs׈��>� Jز�������_ܢ�:���"Y�
�-?��,}�=.a���bp(�bڟ��| ����(}A��~�����������#";��z3�>��"R���"L�f��(���(���>,ջ�?���3I�&�� ��f�r��| ���&��d��W�b�����2q+Yp��Nd�C������=I�J̗�Ⲅ�#f����ֻ�Ԥ̀�&��]���0��>{����}�      \      x������ � �      ^   �   x�e��	�PDϻU�����f,�' x���bA��'��f;r>񰇝y3�^pA�3�5��xO�	�謦��e� f4FsTOx���Z��P���hm�u���%��K���@��BrZn��D%ގ)���-����zk      `   w  x����N�@���s��d�c{<���N�q��R��F�$6!+`ò]�*�OP)B E��3�ߨ�Q�.���ft��}矡��γ+z�]��C�gL�ЈR�١m��1�Y3
� a����P�$B�UGJ�?���:i�:I�lx,I�Dj(N$��[�z5�m�'M0�����v��2��[�삑���f������tU�?�ev�����ČG�3��OC�8���#A�D�$v+����h󙉍ԏRc��%��`���𓐾}h�����X�:�Q3�`[?�͎tMޜ&P�jK{�-�`�M�(:eWa�O:�)�*_���7E�Y��X�yD8�?���W>�c�hRđB ��9~� > �6u�F kj�Y�F�@;`���X븻;;��I7j�𸾳��bi`�/p�]��1 z�ЯE`eT��a�]�rpDC¢)���1�6��>�P��`d��UF�vX�'�m�~Ȟ��`�S�U��}Y�j޸5��T��� ǽ���(ˣ���1-���P`�0��U*/���ӿ��0'p<��7��۲����`�w�٬�'=�o@KA��}e�-�nC�R���j)�W������ϛ� X$r�T��#[�T�
ѯ�     