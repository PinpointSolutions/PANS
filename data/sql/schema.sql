CREATE TABLE degrees (id INT AUTO_INCREMENT, degree TEXT NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE majors (id INT AUTO_INCREMENT, major TEXT NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE nomination_round (deadline DATE, PRIMARY KEY(deadline)) ENGINE = INNODB;
CREATE TABLE projects (id INT AUTO_INCREMENT, title VARCHAR(120), organisation VARCHAR(120), description TEXT, has_additional_info TINYINT(1), has_gpa_cutoff TINYINT(1), degree_ids VARCHAR(64), major_ids VARCHAR(64), skill_set_ids VARCHAR(64), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE project_allocations (id INT AUTO_INCREMENT, project_id INT, snum INT, INDEX project_id_idx (project_id), INDEX snum_idx (snum), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE skill_sets (id INT AUTO_INCREMENT, skill TEXT NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE student_users (snum INT, first_name VARCHAR(64), last_name VARCHAR(64), pass_fail_pm TINYINT(1), major_ids VARCHAR(32), degree_ids VARCHAR(32), skill_set_ids VARCHAR(32), gpa FLOAT(18, 2) NOT NULL, proj_pref1 INT, proj_pref2 INT, proj_pref3 INT, proj_pref4 INT, proj_pref5 INT, y_stu_pref1 INT, y_stu_pref2 INT, y_stu_pref3 INT, y_stu_pref4 INT, y_stu_pref5 INT, n_stu_pref1 INT, n_stu_pref2 INT, n_stu_pref3 INT, n_stu_pref4 INT, n_stu_pref5 INT, proj_just1 TEXT, proj_just2 TEXT, proj_just3 TEXT, proj_just4 TEXT, proj_just5 TEXT, form_completed TINYINT(1) DEFAULT '0' NOT NULL, flag TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX proj_pref1_idx (proj_pref1), INDEX proj_pref2_idx (proj_pref2), INDEX proj_pref3_idx (proj_pref3), INDEX proj_pref4_idx (proj_pref4), INDEX proj_pref5_idx (proj_pref5), INDEX y_stu_pref1_idx (y_stu_pref1), INDEX y_stu_pref2_idx (y_stu_pref2), INDEX y_stu_pref3_idx (y_stu_pref3), INDEX y_stu_pref4_idx (y_stu_pref4), INDEX y_stu_pref5_idx (y_stu_pref5), INDEX n_stu_pref1_idx (n_stu_pref1), INDEX n_stu_pref2_idx (n_stu_pref2), INDEX n_stu_pref3_idx (n_stu_pref3), INDEX n_stu_pref4_idx (n_stu_pref4), INDEX n_stu_pref5_idx (n_stu_pref5), PRIMARY KEY(snum)) ENGINE = INNODB;
CREATE TABLE sf_guard_forgot_password (id BIGINT AUTO_INCREMENT, user_id BIGINT NOT NULL, unique_key VARCHAR(255), expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group_permission (group_id BIGINT, permission_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(group_id, permission_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_permission (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_remember_key (id BIGINT AUTO_INCREMENT, user_id BIGINT, remember_key VARCHAR(32), ip_address VARCHAR(50), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user (id BIGINT AUTO_INCREMENT, first_name VARCHAR(255), last_name VARCHAR(255), email_address VARCHAR(255) NOT NULL UNIQUE, username VARCHAR(128) NOT NULL UNIQUE, algorithm VARCHAR(128) DEFAULT 'sha1' NOT NULL, salt VARCHAR(128), password VARCHAR(128), is_active TINYINT(1) DEFAULT '1', is_super_admin TINYINT(1) DEFAULT '0', last_login DATETIME, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX is_active_idx_idx (is_active), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_group (user_id BIGINT, group_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, group_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_permission (user_id BIGINT, permission_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, permission_id)) ENGINE = INNODB;
ALTER TABLE project_allocations ADD CONSTRAINT project_allocations_snum_student_users_snum FOREIGN KEY (snum) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE project_allocations ADD CONSTRAINT project_allocations_project_id_projects_id FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_y_stu_pref5_student_users_snum FOREIGN KEY (y_stu_pref5) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_y_stu_pref4_student_users_snum FOREIGN KEY (y_stu_pref4) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_y_stu_pref3_student_users_snum FOREIGN KEY (y_stu_pref3) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_y_stu_pref2_student_users_snum FOREIGN KEY (y_stu_pref2) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_y_stu_pref1_student_users_snum FOREIGN KEY (y_stu_pref1) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_proj_pref5_projects_id FOREIGN KEY (proj_pref5) REFERENCES projects(id) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_proj_pref4_projects_id FOREIGN KEY (proj_pref4) REFERENCES projects(id) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_proj_pref3_projects_id FOREIGN KEY (proj_pref3) REFERENCES projects(id) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_proj_pref2_projects_id FOREIGN KEY (proj_pref2) REFERENCES projects(id) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_proj_pref1_projects_id FOREIGN KEY (proj_pref1) REFERENCES projects(id) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_n_stu_pref5_student_users_snum FOREIGN KEY (n_stu_pref5) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_n_stu_pref4_student_users_snum FOREIGN KEY (n_stu_pref4) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_n_stu_pref3_student_users_snum FOREIGN KEY (n_stu_pref3) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_n_stu_pref2_student_users_snum FOREIGN KEY (n_stu_pref2) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE student_users ADD CONSTRAINT student_users_n_stu_pref1_student_users_snum FOREIGN KEY (n_stu_pref1) REFERENCES student_users(snum) ON DELETE CASCADE;
ALTER TABLE sf_guard_forgot_password ADD CONSTRAINT sf_guard_forgot_password_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_remember_key ADD CONSTRAINT sf_guard_remember_key_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
