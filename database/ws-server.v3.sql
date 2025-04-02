CREATE DATABASE WS_SERVER

USE WS_SERVER

CREATE TABLE USERS 
(
   IDUser 		CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   Username 	VARCHAR(255) UNIQUE NOT NULL,
   Email 		VARCHAR(255) UNIQUE NOT NULL,
   Password 	VARCHAR(255) NOT NULL,
   DisplayName VARCHAR(255),
   Avatar 		VARCHAR(255),
   Cover 		VARCHAR(255),
   IsDeleted 	BOOLEAN NOT NULL DEFAULT FALSE
)

CREATE TABLE TEAM
(
   IDTeam            CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDLeader          CHAR(36) NOT NULL,
   TeamName          VARCHAR(255) NOT NULL,
   TeamSize          INT NOT NULL,
   TeamDescription   TEXT,
   IsDeleted         BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDLeader) REFERENCES USERS (IDUser)
)

CREATE TABLE TEAM_MEMBERS
(
   IDTeamMember      CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDTeam            CHAR(36) NOT NULL,
   IDUser            CHAR(36) NOT NULL,
   RoleInTeam        ENUM('Leader', 'Member') NOT NULL DEFAULT 'Member',
   JoinAt            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   IsDeleted         BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDTeam) REFERENCES TEAM (IDTeam),
   FOREIGN KEY (IDUser) REFERENCES USERS (IDUser),
   CONSTRAINT UNIQUE_TEAM_USER UNIQUE (IDTeam, IDUser)
)

CREATE TABLE WORKSPACE
(
   IDWorkSpace          CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDUser               CHAR(36) NOT NULL,
   WorkSpaceName        VARCHAR(255) NOT NULL,
   WorkSpaceDescription TEXT,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE, 
   FOREIGN KEY (IDUser) REFERENCES USERS (IDUser)
)

CREATE TABLE WORKSPACE_ACCESS
(
   IDWorkSpaceAccess    CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDWorkSpace          CHAR(36) NOT NULL,
   IDCollaborator       CHAR(36) NOT NULL,
   Permission           ENUM('Owner', 'Edit', 'View') NOT NULL DEFAULT 'View',
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDWorkSpace) REFERENCES WORkSPACE (IDWorkSpace),
   FOREIGN KEY (IDCollaborator) REFERENCES USERS (IDUser),
   CONSTRAINT UNIQUE_WORKSPACE_USER UNIQUE (IDWorkSpace, IDCollaborator)
)

CREATE TABLE PRICING
(
   IDPricing            CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   PricingName          VARCHAR(255) UNIQUE NOT NULL,
   Pricing              DECIMAL(10, 2) NOT NULL,
   PricingDescription   TEXT,
   ProjectLimit         INT NOT NULL,
   TeamLimit            INT NOT NULL,
   UnLimitedProjects    BOOLEAN NOT NULL DEFAULT FALSE,
   Duration             ENUM('Monthly', 'Yearly') NOT NULL,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE
)

CREATE TABLE PRICING_PLAN
(
   IDPricingPlan        CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDPricing            CHAR(36) NOT NULL,
   IDUser               CHAR(36) NOT NULL,
   SubscribedAt         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   ExpiredAt            TIMESTAMP NOT NULL,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDPricing) REFERENCES PRICING (IDPricing),
   FOREIGN KEY (IDUser) REFERENCES USERS (IDUser),
   CONSTRAINT UNIQUE_PRICING_USER UNIQUE (IDPricing, IDUser)
)

CREATE TABLE PRICING_FEATURE
(
   IDFeature            CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDPricing            CHAR(36) NOT NULL,
   Feature              VARCHAR(255) UNIQUE NOT NULL,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDPricing) REFERENCES PRICING (IDPricing)
)

CREATE TABLE QUICK_ACCESS_BAR
(
   IDQuickAccessBar     CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDUser               CHAR(36) NOT NULL,
   URL                  VARCHAR(255) NOT NULL,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDUser) REFERENCES USERS (IDUser)
)

CREATE TABLE WIDGET
(
   IDWidget             CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDWorkSpace          CHAR(36) NOT NULL,
   WidgetType           ENUM('Note', 'Table', 'Kanban Board', 'Image', 'Schedule', 'File') NOT NULL DEFAULT 'Note',
   Z_Index              INT NOT NULL,
   Width                INT NOT NULL,
   Height               INT NOT NULL,
   Color                VARCHAR(255),
   PositionX            INT NOT NULL,
   PositionY            INT NOT NULL,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   IDWidgetChild        CHAR(36) ,
   FOREIGN KEY (IDWorkSpace) REFERENCES WORKSPACE (IDWorkSpace),
   CONSTRAINT UNIQUE_Z_Index UNIQUE (IDWorkSpace, Z_Index)
)

CREATE TABLE NOTE
(
   IDNote               CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDWidget             CHAR(36) NOT NULL,
   Author               CHAR(36) NOT NULL,
   Title                VARCHAR(255) NOT NULL,
   Content              TEXT,
   CreatedAt            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   IsPublic             BOOLEAN NOT NULL DEFAULT FALSE,
   Thumbnail            VARCHAR(255),
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (Author) REFERENCES USERS (IDUser),
   FOREIGN KEY (IDWidget) REFERENCES WIDGET (IDWidget)
)

CREATE TABLE NOTE_ATTACHMENT
(
   IDNoteAttachment     CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDNote               CHAR(36) NOT NULL,
   FileName             VARCHAR(255) NOT NULL,
   FileType             ENUM('Image', 'Document', 'Other') NOT NULL,
   URL                  VARCHAR(255) NOT NULL,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDNote) REFERENCES NOTE (IDNote)
)

CREATE TABLE PROJECT
(
   IDProject            CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDTeam               CHAR(36) NOT NULL,
   ProjectName          VARCHAR(255) NOT NULL,
   ProjectDescription   TEXT,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDTeam) REFERENCES TEAM (IDTeam)
)

CREATE TABLE PROJECT_ACCESS
(
   IDProjectAccess      CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDProject            CHAR(36) NOT NULL,
   IDCollaborator       CHAR(36) NOT NULL,
   Permission           ENUM('Owner', 'Edit', 'View') NOT NULL DEFAULT 'View',
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDProject) REFERENCES PROJECT (IDProject),
   FOREIGN KEY (IDCollaborator) REFERENCES USERS (IDUser),
   CONSTRAINT UNIQUE_PROJECT_USER UNIQUE (IDProject, IDCollaborator)
)

CREATE TABLE TAG
(
   IDTag                CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDProject            CHAR(36) NOT NULL,
   TagName              VARCHAR(255) NOT NULL,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDProject) REFERENCES PROJECT (IDProject),
   CONSTRAINT UNIQUE_PROJECT_TAG_NAME UNIQUE (IDProject, TagName)
)

CREATE TABLE STATUS
(
   IDStatus             CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDProject            CHAR(36) NOT NULL,
   Status               VARCHAR(255) NOT NULL,
   StatusOrder          INT NOT NULL,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDProject) REFERENCES PROJECT (IDProject),
   CONSTRAINT UNIQUE_STATUS_NAME UNIQUE (IDProject, Status)
)

CREATE TABLE TASK
(
   IDTask               CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDProject            CHAR(36) NOT NULL,
   IDStatus             CHAR(36) NOT NULL,
   IDTag                CHAR(36) NOT NULL,
   IDAssignee           CHAR(36) NOT NULL,
   TaskName             VARCHAR(255) NOT NULL,
   Priority             ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Low',
   CreateAt             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   StartDay             TIMESTAMP,
   EndDay               TIMESTAMP,
   DueDay               TIMESTAMP,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDProject) REFERENCES PROJECT (IDProject),
   FOREIGN KEY (IDStatus) REFERENCES STATUS (IDStatus),
   FOREIGN KEY (IDTag) REFERENCES TAG (IDTag),
   FOREIGN KEY (IDAssignee) REFERENCES USERS (IDUser)
)

CREATE TABLE TASK_ATTACHMENT
(
   IDTaskAttachment     CHAR(36) PRIMARY KEY DEFAULT(UUID()),
   IDTask               CHAR(36) NOT NULL,
   UploadedBy           CHAR(36) NOT NULL,
   FileName             VARCHAR(255) NOT NULL,
   FileType             ENUM('Image', 'Document', 'Other') NOT NULL,
   URL                  VARCHAR(255) NOT NULL,
   UploadedAt           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   IsFinalFile          BOOLEAN NOT NULL DEFAULT FALSE,
   IsDeleted            BOOLEAN NOT NULL DEFAULT FALSE,
   FOREIGN KEY (IDTask) REFERENCES TASK (IDTask),
   FOREIGN KEY (UploadedBy) REFERENCES USERS (IDUser),
   CONSTRAINT UNIQUE_TASK_ATTACHMENT UNIQUE (IDTask, IDTaskAttachment),
   CONSTRAINT UNIQUE_TASK_ATTACHMENT_USER UNIQUE (UploadedBy, IDTaskAttachment)
)