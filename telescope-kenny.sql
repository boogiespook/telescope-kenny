--
-- PostgreSQL database dump
--

-- Dumped from database version 15.4
-- Dumped by pg_dump version 15.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: capability_history(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.capability_history() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO
        capability_history(capability_id,flag)
        VALUES(new.id,new.flag_id);
           RETURN new;
END;
$$;


ALTER FUNCTION public.capability_history() OWNER TO telescope;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: capability; Type: TABLE; Schema: public; Owner: telescope
--

CREATE TABLE public.capability (
    id integer NOT NULL,
    domain_id integer,
    flag_id integer,
    description character varying(128),
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.capability OWNER TO telescope;

--
-- Name: capability_history; Type: TABLE; Schema: public; Owner: telescope
--

CREATE TABLE public.capability_history (
    id integer NOT NULL,
    capability_id integer,
    flag integer,
    updated timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.capability_history OWNER TO telescope;

--
-- Name: capability_history_id_seq; Type: SEQUENCE; Schema: public; Owner: telescope
--

CREATE SEQUENCE public.capability_history_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.capability_history_id_seq OWNER TO telescope;

--
-- Name: capability_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: telescope
--

ALTER SEQUENCE public.capability_history_id_seq OWNED BY public.capability_history.id;


--
-- Name: capability_id_seq; Type: SEQUENCE; Schema: public; Owner: telescope
--

CREATE SEQUENCE public.capability_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.capability_id_seq OWNER TO telescope;

--
-- Name: capability_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: telescope
--

ALTER SEQUENCE public.capability_id_seq OWNED BY public.capability.id;


--
-- Name: domain; Type: TABLE; Schema: public; Owner: telescope
--

CREATE TABLE public.domain (
    id integer NOT NULL,
    description character varying(128),
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.domain OWNER TO telescope;

--
-- Name: domain_id_seq; Type: SEQUENCE; Schema: public; Owner: telescope
--

CREATE SEQUENCE public.domain_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.domain_id_seq OWNER TO telescope;

--
-- Name: domain_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: telescope
--

ALTER SEQUENCE public.domain_id_seq OWNED BY public.domain.id;


--
-- Name: flag; Type: TABLE; Schema: public; Owner: telescope
--

CREATE TABLE public.flag (
    id integer NOT NULL,
    description character varying(128),
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.flag OWNER TO telescope;

--
-- Name: flag_id_seq; Type: SEQUENCE; Schema: public; Owner: telescope
--

CREATE SEQUENCE public.flag_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.flag_id_seq OWNER TO telescope;

--
-- Name: flag_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: telescope
--

ALTER SEQUENCE public.flag_id_seq OWNED BY public.flag.id;


--
-- Name: integration_id_seq; Type: SEQUENCE; Schema: public; Owner: telescope
--

CREATE SEQUENCE public.integration_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.integration_id_seq OWNER TO telescope;

--
-- Name: integration_methods; Type: TABLE; Schema: public; Owner: telescope
--

CREATE TABLE public.integration_methods (
    integration_method_name character varying,
    id bigint NOT NULL
);


ALTER TABLE public.integration_methods OWNER TO telescope;

--
-- Name: integration_methods_id_seq; Type: SEQUENCE; Schema: public; Owner: telescope
--

CREATE SEQUENCE public.integration_methods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000
    CACHE 1;


ALTER TABLE public.integration_methods_id_seq OWNER TO telescope;

--
-- Name: integration_methods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: telescope
--

ALTER SEQUENCE public.integration_methods_id_seq OWNED BY public.integration_methods.id;


--
-- Name: integrations; Type: TABLE; Schema: public; Owner: telescope
--

CREATE TABLE public.integrations (
    integration_id bigint DEFAULT nextval('public.integration_id_seq'::regclass) NOT NULL,
    capability_id integer,
    url character varying(300),
    "user" character varying(100),
    password character varying(100),
    token character varying(5000),
    success_criteria character varying(100),
    last_update timestamp with time zone,
    integration_name character varying(100),
    integration_method_id bigint,
    hash character(5)
);


ALTER TABLE public.integrations OWNER TO telescope;

--
-- Name: profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.profiles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.profiles_id_seq OWNER TO telescope;

--
-- Name: profiles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.profiles (
    id integer DEFAULT nextval('public.profiles_id_seq'::regclass) NOT NULL,
    name character varying(128),
    description character varying(128),
    domains integer[]
);


ALTER TABLE public.profiles OWNER TO telescope;

--
-- Name: capability id; Type: DEFAULT; Schema: public; Owner: telescope
--

ALTER TABLE ONLY public.capability ALTER COLUMN id SET DEFAULT nextval('public.capability_id_seq'::regclass);


--
-- Name: capability_history id; Type: DEFAULT; Schema: public; Owner: telescope
--

ALTER TABLE ONLY public.capability_history ALTER COLUMN id SET DEFAULT nextval('public.capability_history_id_seq'::regclass);


--
-- Name: domain id; Type: DEFAULT; Schema: public; Owner: telescope
--

ALTER TABLE ONLY public.domain ALTER COLUMN id SET DEFAULT nextval('public.domain_id_seq'::regclass);


--
-- Name: flag id; Type: DEFAULT; Schema: public; Owner: telescope
--

ALTER TABLE ONLY public.flag ALTER COLUMN id SET DEFAULT nextval('public.flag_id_seq'::regclass);


--
-- Name: integration_methods id; Type: DEFAULT; Schema: public; Owner: telescope
--

ALTER TABLE ONLY public.integration_methods ALTER COLUMN id SET DEFAULT nextval('public.integration_methods_id_seq'::regclass);


--
-- Data for Name: capability; Type: TABLE DATA; Schema: public; Owner: telescope
--

INSERT INTO public.capability VALUES (52, 18, 1, 'Automation and Orchestration', '2023-10-06 15:27:06.048685');
INSERT INTO public.capability VALUES (53, 18, 2, 'Governance', '2023-10-06 15:27:16.894685');
INSERT INTO public.capability VALUES (54, 18, 2, 'Visibility and Analytics', '2023-10-06 15:27:29.431084');
INSERT INTO public.capability VALUES (9, 1, 2, 'Container Protection', '2023-01-05 12:06:38.023359');
INSERT INTO public.capability VALUES (8, 1, 2, 'Platform Hardening', '2023-01-05 12:06:29.110498');
INSERT INTO public.capability VALUES (43, 14, 2, 'Accessible Applications', '2023-10-06 15:22:05.877366');
INSERT INTO public.capability VALUES (44, 14, 2, 'Application Access', '2023-10-06 15:22:20.977352');
INSERT INTO public.capability VALUES (45, 14, 2, 'Application Security Testing', '2023-10-06 15:22:42.86989');
INSERT INTO public.capability VALUES (1, 1, 2, 'Secure Images', '2023-01-05 12:05:28.265165');
INSERT INTO public.capability VALUES (2, 2, 2, 'Classification', '2023-01-05 12:05:28.270549');
INSERT INTO public.capability VALUES (3, 2, 2, 'Encryption at Rest', '2023-01-05 12:05:28.272751');
INSERT INTO public.capability VALUES (10, 2, 2, 'Vulnerability Scanning', '2023-01-05 12:06:50.545384');
INSERT INTO public.capability VALUES (60, 3, 2, 'DAST', '2023-10-12 12:46:45.190308');
INSERT INTO public.capability VALUES (59, 3, 1, 'SAST', '2023-10-12 12:46:39.490502');
INSERT INTO public.capability VALUES (61, 3, 1, 'Validated SBOM', '2023-10-12 12:46:59.907279');
INSERT INTO public.capability VALUES (46, 14, 2, 'Application Threat Protections', '2023-10-06 15:23:00.305145');
INSERT INTO public.capability VALUES (37, 14, 2, 'Device Threat Protection', '2023-10-06 15:19:49.58944');
INSERT INTO public.capability VALUES (38, 14, 2, 'Policy Enforcement', '2023-10-06 15:20:03.379245');
INSERT INTO public.capability VALUES (35, 14, 2, 'Resource Access', '2023-10-06 15:19:15.829315');
INSERT INTO public.capability VALUES (36, 14, 2, 'Supply Chain Risk Management', '2023-10-06 15:19:34.224251');
INSERT INTO public.capability VALUES (47, 15, 2, 'Data Access', '2023-10-06 15:23:33.053765');
INSERT INTO public.capability VALUES (48, 15, 2, 'Data Availability', '2023-10-06 15:23:44.105081');
INSERT INTO public.capability VALUES (49, 15, 2, 'Data Categorization', '2023-10-06 15:23:56.604482');
INSERT INTO public.capability VALUES (50, 15, 1, 'Data Encryption', '2023-10-06 15:24:08.347428');
INSERT INTO public.capability VALUES (51, 15, 2, 'Data Inventory Management', '2023-10-06 15:24:21.317944');
INSERT INTO public.capability VALUES (39, 16, 2, 'Network Resilience', '2023-10-06 15:20:43.423782');
INSERT INTO public.capability VALUES (40, 16, 1, 'Network Segmentation', '2023-10-06 15:20:56.914121');
INSERT INTO public.capability VALUES (41, 16, 2, 'Network Traffic Management', '2023-10-06 15:21:10.478316');
INSERT INTO public.capability VALUES (42, 16, 2, 'Traffic Encryption', '2023-10-06 15:21:24.9588');
INSERT INTO public.capability VALUES (31, 17, 1, 'Access Management', '2023-10-06 15:08:15.534594');
INSERT INTO public.capability VALUES (32, 17, 1, 'Authentication', '2023-10-06 15:17:59.242437');
INSERT INTO public.capability VALUES (33, 17, 1, 'Identity Stores', '2023-10-06 15:18:16.639232');
INSERT INTO public.capability VALUES (62, 4, 2, 'Encryption in Transit', '2023-10-12 12:52:03.309656');
INSERT INTO public.capability VALUES (63, 4, 2, 'Firewalls', '2023-10-12 12:52:18.607406');
INSERT INTO public.capability VALUES (64, 4, 2, 'Policy', '2023-10-12 12:52:33.478537');
INSERT INTO public.capability VALUES (65, 5, 2, 'Asset Management', '2023-10-12 12:53:39.222796');
INSERT INTO public.capability VALUES (67, 5, 2, 'IDS/IPS', '2023-10-12 12:54:56.844442');
INSERT INTO public.capability VALUES (68, 5, 2, 'SIEM', '2023-10-12 12:55:08.580479');
INSERT INTO public.capability VALUES (55, 13, 2, 'Device Threat Protection', '2023-10-06 15:32:38.35061');
INSERT INTO public.capability VALUES (56, 13, 2, 'Policy Enforcement', '2023-10-06 15:33:05.119296');
INSERT INTO public.capability VALUES (57, 13, 2, 'Resource Access', '2023-10-06 15:33:19.960765');
INSERT INTO public.capability VALUES (34, 13, 2, 'Risk Assessments', '2023-10-06 15:18:30.44223');


--
-- Data for Name: capability_history; Type: TABLE DATA; Schema: public; Owner: telescope
--



--
-- Data for Name: domain; Type: TABLE DATA; Schema: public; Owner: telescope
--

INSERT INTO public.domain VALUES (13, 'Devices', '2023-10-06 14:55:04.836871');
INSERT INTO public.domain VALUES (14, 'Apps & Workloads', '2023-10-06 14:55:22.032712');
INSERT INTO public.domain VALUES (17, 'Identity', '2023-10-06 14:56:00.614473');
INSERT INTO public.domain VALUES (18, 'Cross-Cutting ZTA', '2023-10-06 14:56:13.832703');
INSERT INTO public.domain VALUES (1, 'Infrastructure', '2023-01-05 12:04:58.133484');
INSERT INTO public.domain VALUES (2, 'Data', '2023-01-05 12:04:58.145907');
INSERT INTO public.domain VALUES (3, 'Application', '2023-01-05 12:04:58.147519');
INSERT INTO public.domain VALUES (5, 'Visibility', '2023-01-05 12:04:58.151347');
INSERT INTO public.domain VALUES (4, 'Networks', '2023-01-05 12:04:58.149359');
INSERT INTO public.domain VALUES (16, 'Networks ZTA', '2023-10-06 14:55:48.372071');
INSERT INTO public.domain VALUES (15, 'Data ZTA', '2023-10-06 14:55:35.744453');


--
-- Data for Name: flag; Type: TABLE DATA; Schema: public; Owner: telescope
--

INSERT INTO public.flag VALUES (1, 'red', '2023-01-05 12:06:10.998784');
INSERT INTO public.flag VALUES (2, 'green', '2023-01-05 12:06:17.838214');


--
-- Data for Name: integration_methods; Type: TABLE DATA; Schema: public; Owner: telescope
--

INSERT INTO public.integration_methods VALUES ('telescopeComplianceRhacs', 1);
INSERT INTO public.integration_methods VALUES ('telescopeSecureProtocols', 6);
INSERT INTO public.integration_methods VALUES ('telescopeTestApi', 11);


--
-- Data for Name: integrations; Type: TABLE DATA; Schema: public; Owner: telescope
--

INSERT INTO public.integrations VALUES (6, 8, 'https://central-stackrox.apps.test-env.aws.project-telescope.com/v1/compliance/runresults?standardId=ocp4-cis&clusterId=569a487a-1cdd-4431-8ab3-0528e10b2124', '', '', 'eyJhbGciOiJSUzI1NiIsImtpZCI6Imp3dGswIiwidHlwIjoiSldUIn0.eyJhdWQiOlsiaHR0cHM6Ly9zdGFja3JveC5pby9qd3Qtc291cmNlcyNhcGktdG9rZW5zIl0sImV4cCI6MTcxNTI0NDcyNiwiaWF0IjoxNjgzNzA4NzI2LCJpc3MiOiJodHRwczovL3N0YWNrcm94LmlvL2p3dCIsImp0aSI6ImRlZGQ1OTU0LTI4NTAtNGJlNi04ZmQyLWFmNmZmNWM0OTAzOSIsIm5hbWUiOiJzdGFja3JveC1jaHJpc2oiLCJyb2xlcyI6WyJBZG1pbiJdfQ.WM-7WVofWSvQmHUj6dnw8Ao5AkRbKGCMSFIcil-gJEUt1Zvmx0Ug4q1X_-6zD-Qk-RQ9nZUmbLCWc2YJOYECICaFkwSRrZecT19HOMpK6sed-lK6zvksxtaijP0HXbn_e7GGHzj-n8XzZG15WFtwbkNbwucNH6brl8MXmioQslphNYWvb_MHl-Ix3evV_1IVBfF4q4C_A5u6kXqb-SoCw65AHOBHAU23XCwvsi5PIyB7qYTggLW1ee4j2WxF3_YVSsH-ivA6qRXR_zZu1_tCXYQFZ8Vrrw0FMBYuPYNLlrZ1MDPSUn5misUl-I8PeHMxDeVON3yUAqOEGNjYTXiPkgWh8hlgUo1vdVK-tKokSa5n1b8i6JmjOSgRpA5TGVfRJxLul3TJH9JZn0hBAjMeXsAM6O_fDkWwda-Hg9Mvtk_M9bCp4y8dIZx4A4A4x-sfq2VuGezGM-mX1hlkYIY043BViGc3C2Pw-GO_Yzt-wOeLj37ALivGEN7vG5ozQKa265_jdzPLmGBY5_fsYfJgOKMdZ3KCl7sw0CQ86Gwq2CYb4PfitYPKXqeSwgB_a4mpph5TVvJAk_K81nAzuVkiLrj2FqJrnuwmLkec1MAaGKj4NNwMAs08a6Rl01KPn7KVCCgaGaJWevKNyVn1yo_Zy0VQ3eZFjZRacvUWBS0OPdg', '90', '2023-05-11 08:43:44.649431+01', 'RHACS Compliance Score (Telescope)', 1, 'PX8VK');
INSERT INTO public.integrations VALUES (22, 49, 'https://localhost/alal', 'admin', 'admin', '', 'Yes', NULL, 'CJ Test', NULL, 'EQTVW');


--
-- Data for Name: profiles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.profiles VALUES (2, 'ZTA', 'ZTA domains', '{13,14,15,16,17,18}');
INSERT INTO public.profiles VALUES (1, 'Core', 'Default domains', '{1,2,3,4,5}');


--
-- Name: capability_history_id_seq; Type: SEQUENCE SET; Schema: public; Owner: telescope
--

SELECT pg_catalog.setval('public.capability_history_id_seq', 727, true);


--
-- Name: capability_id_seq; Type: SEQUENCE SET; Schema: public; Owner: telescope
--

SELECT pg_catalog.setval('public.capability_id_seq', 68, true);


--
-- Name: domain_id_seq; Type: SEQUENCE SET; Schema: public; Owner: telescope
--

SELECT pg_catalog.setval('public.domain_id_seq', 19, true);


--
-- Name: flag_id_seq; Type: SEQUENCE SET; Schema: public; Owner: telescope
--

SELECT pg_catalog.setval('public.flag_id_seq', 2, true);


--
-- Name: integration_id_seq; Type: SEQUENCE SET; Schema: public; Owner: telescope
--

SELECT pg_catalog.setval('public.integration_id_seq', 22, true);


--
-- Name: integration_methods_id_seq; Type: SEQUENCE SET; Schema: public; Owner: telescope
--

SELECT pg_catalog.setval('public.integration_methods_id_seq', 11, true);


--
-- Name: profiles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.profiles_id_seq', 21, true);


--
-- Name: capability capability_pkey; Type: CONSTRAINT; Schema: public; Owner: telescope
--

ALTER TABLE ONLY public.capability
    ADD CONSTRAINT capability_pkey PRIMARY KEY (id);


--
-- Name: domain domain_pkey; Type: CONSTRAINT; Schema: public; Owner: telescope
--

ALTER TABLE ONLY public.domain
    ADD CONSTRAINT domain_pkey PRIMARY KEY (id);


--
-- Name: flag flag_pkey; Type: CONSTRAINT; Schema: public; Owner: telescope
--

ALTER TABLE ONLY public.flag
    ADD CONSTRAINT flag_pkey PRIMARY KEY (id);


--
-- Name: integrations integrations_pkey; Type: CONSTRAINT; Schema: public; Owner: telescope
--

ALTER TABLE ONLY public.integrations
    ADD CONSTRAINT integrations_pkey PRIMARY KEY (integration_id);


--
-- Name: capability capability_trigger_copy; Type: TRIGGER; Schema: public; Owner: telescope
--

CREATE TRIGGER capability_trigger_copy AFTER UPDATE ON public.capability FOR EACH ROW EXECUTE FUNCTION public.capability_history();


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: pg_database_owner
--

GRANT ALL ON SCHEMA public TO telescope;


--
-- PostgreSQL database dump complete
--

