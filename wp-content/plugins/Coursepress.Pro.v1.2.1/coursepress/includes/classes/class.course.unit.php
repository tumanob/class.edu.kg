<?php

if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( !class_exists( 'Unit' ) ) {

	class Unit {

		var $id			 = '';
		var $output		 = 'OBJECT';
		var $unit		 = array();
		var $details;
		var $course_id	 = '';
		var $status		 = array();

		function __construct( $id = '', $output = 'OBJECT' ) {
			$this->id		 = $id;
			$this->output	 = $output;
			$this->details	 = get_post( $this->id, $this->output );

			$this->course_id = $this->get_parent_course_id();
		}

		function Unit( $id = '', $output = 'OBJECT' ) {
			$this->__construct( $id, $output );
		}

		function get_unit() {

			$unit = get_post( $this->id, $this->output );

			if ( !empty( $unit ) ) {

				if ( $unit->post_title == '' ) {
					$unit->post_title = __( 'Untitled', 'cp' );
				}

				if ( $unit->post_status == 'private' || $unit->post_status == 'draft' ) {
					$unit->post_status = __( 'unpublished', 'cp' );
				}

				if ( !isset( $unit->details->post_name ) ) {
					//$unit->details->post_name = '';
				}

				return $unit;
			} else {
				return false;
			}
		}

		function is_unit_available( $unit_id = '' ) {

			if ( $unit_id == '' ) {
				$unit_id = $this->id;
			}

			$unit_details = $this->get_unit( $unit_id );

			$current_date = ( date( 'Y-m-d', current_time( 'timestamp', 0 ) ) );

			/* Check if previous has conditions */
			$previous_unit_id                         = $this->get_previous_unit_from_the_same_course( $unit_id );
			$force_current_unit_completion            = get_post_meta( $previous_unit_id, 'force_current_unit_completion', true );
			$force_current_unit_successful_completion = get_post_meta( $previous_unit_id, 'force_current_unit_successful_completion', true );

			$available = true;

			$completion	= new Course_Completion( $this->course_id );
			$completion->init_student_status();

			$mandatory_done = $completion->unit_all_pages_viewed( $previous_unit_id ) && $completion->unit_all_mandatory_answered( $previous_unit_id );
			$unit_completed = 100 == $completion->unit_progress( $previous_unit_id );

			$this->status['mandatory_required']['enabled'] = ! empty( $force_current_unit_completion ) && 'on' == $force_current_unit_completion;
			$this->status['mandatory_required']['result'] = $mandatory_done;

			$this->status['completion_required']['enabled'] = ! empty( $force_current_unit_successful_completion ) && 'on' == $force_current_unit_successful_completion;
			$this->status['completion_required']['result'] = $unit_completed;

			$available = $this->status['mandatory_required']['enabled'] ? $this->status['mandatory_required']['result'] : $available;
			$available = $this->status['completion_required']['enabled'] ? $this->status['completion_required']['result'] : $available;

			$this->status['date_restriction']['result'] = $current_date >= $unit_details->unit_availability;

			if ( ! $this->status['date_restriction']['result'] || !$available ) {
				return false;
			} else {
				return true;
			}
		}

		function get_previous_unit_from_the_same_course( $unit_id = '', $post_status = 'publish' ) {

			global $wpdb;

			if ( $unit_id == '' ) {
				$unit_id = $this->id;
			}

			$current_unit_order = get_post_meta( $unit_id, 'unit_order', true );

			/* $args = array(
			  'post_type'		 => 'unit',
			  'post_status'	 => $post_status,
			  'posts_per_page' => 1,
			  'meta_key'		 => 'course_id',
			  'meta_value'	 => $this->course_id,
			  'meta_query'	 => array(
			  array(
			  'key'		 => 'unit_order',
			  'compare'	 => '<',
			  'value'		 => $current_unit_order
			  ),
			  )
			  ); */

			$previous_unit_id = $wpdb->get_var(
			$wpdb->prepare(
			"SELECT p.ID FROM $wpdb->posts p, $wpdb->postmeta pm
				WHERE p.ID = pm.post_id
				AND p.post_parent = %d
				AND pm.meta_key = 'unit_order'
				AND pm.meta_value < %d
				ORDER BY pm.meta_value DESC
				LIMIT 1", $this->course_id, $this->id )
			);

			return $previous_unit_id;

			//$previous_unit = new Unit($previous_unit_id);//get_posts( $args );
			//print_r($results);
			//return $previous_unit;
		}

		function get_unit_page_time_estimation( $unit_id, $page_num ) {

			$unit_pages	 = $this->get_number_of_unit_pages();
			$module		 = new Unit_Module();
			$modules	 = $module->get_modules( $unit_id );


			for ( $i = 1; $i <= $unit_pages; $i++ ) {
				$pages_num		 = 1;
				$total_minutes	 = 0;
				$total_seconds	 = 0;

				foreach ( $modules as $mod ) {
					$class_name		 = $mod->module_type;
					$time_estimation = $mod->time_estimation;

					if ( class_exists( $class_name ) ) {
						$module = new $class_name();

						if ( $module->name == 'page_break_module' ) {
							$pages_num++;
						} else {
							if ( $pages_num == $page_num ) {
								if ( isset( $time_estimation ) && $time_estimation !== '' ) {
									$estimatation = explode( ':', $time_estimation );
									if ( isset( $estimatation[ 0 ] ) ) {
										$total_minutes = $total_minutes + intval( $estimatation[ 0 ] );
									}
									if ( isset( $estimatation[ 1 ] ) ) {
										$total_seconds = $total_seconds + intval( $estimatation[ 1 ] );
									}
								}
							}
						}
					}
				}

				$total_seconds = $total_seconds + ($total_minutes * 60); //converted everything into minutes for easy conversion back to minutes and seconds

				$minutes = floor( $total_seconds / 60 );
				$seconds = $total_seconds % 60;

				if ( $minutes >= 1 || $seconds >= 1 ) {
					return apply_filters( 'cp_unit_time_estimation_minutes_and_seconds_format', ($minutes . ':' . ($seconds <= 9 ? '0' . $seconds : $seconds) . ' min' ) );
				} else {
					return apply_filters( 'cp_unit_time_estimation_na_format', __( 'N/A', 'cp' ) );
				}
			}
		}

		function get_unit_time_estimation( $unit_id ) {
			$module			 = new Unit_Module();
			$modules		 = $module->get_modules( $unit_id );
			$total_minutes	 = 0;
			$total_seconds	 = 0;

			foreach ( $modules as $mod ) {
				$time_estimation = $mod->time_estimation;
				if ( isset( $time_estimation ) && $time_estimation !== '' ) {
					$estimatation = explode( ':', $time_estimation );
					if ( isset( $estimatation[ 0 ] ) ) {
						$total_minutes = $total_minutes + intval( $estimatation[ 0 ] );
					}
					if ( isset( $estimatation[ 1 ] ) ) {
						$total_seconds = $total_seconds + intval( $estimatation[ 1 ] );
					}
				}
			}

			$total_seconds = $total_seconds + ($total_minutes * 60); //converted everything into minutes for easy conversion back to minutes and seconds

			$minutes = floor( $total_seconds / 60 );
			$seconds = $total_seconds % 60;

			if ( $minutes >= 1 || $seconds >= 1 ) {
				return apply_filters( 'cp_unit_time_estimation_minutes_and_seconds_format', ($minutes . ':' . ($seconds <= 9 ? '0' . $seconds : $seconds) . ' min' ) );
			} else {
				return apply_filters( 'cp_unit_time_estimation_na_format', __( 'N/A', 'cp' ) );
			}
		}

		function create_auto_draft( $course_id ) {
			global $user_id;

			$post = array(
				'post_author'	 => $user_id,
				'post_content'	 => '',
				'post_status'	 => 'auto-draft', //$post_status
				'post_title'	 => __( 'Untitled', 'cp' ),
				'post_type'		 => 'unit',
				'post_parent'	 => $course_id
			);

			$post_id = wp_insert_post( $post );

			return $post_id;
		}

		function delete_all_elements_auto_drafts( $unit_id = false ) {
			global $wpdb;

			if ( !$unit_id ) {
				$unit_id = $this->id;
			}

			$unit_id = (int) $unit_id;

			$wpdb->query(
			$wpdb->prepare( "
                DELETE FROM $wpdb->posts
		 WHERE post_parent = %d
                 AND post_status = 'auto-draft'
		", $unit_id
			)
			);
		}

		function update_unit() {
			global $user_id, $last_inserted_unit_id;

			$post_status = 'private';

			if ( isset( $_POST[ 'unit_id' ] ) && $_POST[ 'unit_id' ] != 0 ) {

				$unit_id = ( isset( $_POST[ 'unit_id' ] ) ? $_POST[ 'unit_id' ] : $this->id );

				$unit = get_post( $unit_id, $this->output );

				if ( $_POST[ 'unit_name' ] !== '' && $_POST[ 'unit_name' ] !== __( 'Untitled', 'cp' ) /* && $_POST['unit_description'] !== '' */ ) {
					if ( $unit->post_status !== 'publish' ) {
						$post_status = 'private';
					} else {
						$post_status = 'publish';
					}
				} else {
					$post_status = 'draft';
				}
			}

			$post = array(
				'post_author'	 => $user_id,
				'post_content'	 => '', //$_POST['unit_description']
				'post_status'	 => $post_status, //$post_status
				'post_title'	 => cp_filter_content( $_POST[ 'unit_name' ], true ),
				'post_type'		 => 'unit',
				'post_parent'	 => $_POST[ 'course_id' ]
			);

			if ( isset( $_POST[ 'unit_id' ] ) ) {
				$post[ 'ID' ] = $_POST[ 'unit_id' ]; //If ID is set, wp_insert_post will do the UPDATE instead of insert
			}

			$post_id = wp_insert_post( $post );

			$last_inserted_unit_id = $post_id;

			update_post_meta( $post_id, 'course_id', (int) $_POST[ 'course_id' ] );

			update_post_meta( $post_id, 'unit_availability', cp_filter_content( $_POST[ 'unit_availability' ] ) );

			update_post_meta( $post_id, 'force_current_unit_completion', cp_filter_content( $_POST[ 'force_current_unit_completion' ] ) );
			update_post_meta( $post_id, 'force_current_unit_successful_completion', cp_filter_content( $_POST[ 'force_current_unit_successful_completion' ] ) );

			update_post_meta( $post_id, 'page_title', cp_filter_content( $_POST[ 'page_title' ], true ) );

			update_post_meta( $post_id, 'show_page_title', cp_filter_content( $_POST[ 'show_page_title_field' ] ) );

			if ( !get_post_meta( $post_id, 'unit_order', true ) ) {
				update_post_meta( $post_id, 'unit_order', $post_id );
			}

			return $post_id;
		}

		function get_unit_page_name( $page_number ) {
			return !empty( $this->details->page_title ) ? $this->details->page_title[ (int) ($page_number - 1) ] : '';
		}

		function delete_unit( $force_delete ) {
			$wpdb;
			wp_delete_post( $this->id, $force_delete ); //Whether to bypass trash and force deletion
			//Delete unit modules

			$args = array(
				'posts_per_page' => -1,
				'post_parent'	 => $this->id,
				'post_type'		 => 'module',
				'post_status'	 => 'any',
			);

			$units_modules = get_posts( $args );

			foreach ( $units_modules as $units_module ) {
				$module = new Unit_Module( $units_module->ID );
				$module->delete_module( true );
			}
		}

		function change_status( $post_status ) {
			$post = array(
				'ID'			 => $this->id,
				'post_status'	 => $post_status,
			);

			// Update the post status
			wp_update_post( $post );
		}

		function can_show_permalink() {
			$unit = $this->get_unit();
			if ( $unit->post_status !== 'draft' ) {
				return true;
			} else {
				return false;
			}
		}

		function get_permalink( $course_id = '' ) {
			global $course_slug;
			global $units_slug;

			if ( empty( $course_id ) ) {
				$course_id = get_post_meta( $this->id, 'course_id', true );
			}

			$course	 = new Course( $course_id );
			$course	 = $course->get_course();

			$unit_permalink = trailingslashit( home_url() . '/' ) . trailingslashit( $course_slug . '/' ) . trailingslashit( isset( $course->post_name ) ? $course->post_name : '' . '/'  ) . trailingslashit( $units_slug . '/' ) . trailingslashit( isset( $this->details->post_name ) ? $this->details->post_name : '' . '/'  );

			return $unit_permalink;
		}

		function get_unit_id_by_name( $slug ) {
			global $wpdb;
			$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'unit'", $slug ) );
			return $id;
		}

		function get_parent_course_id( $unit_id = '' ) {
			if ( $unit_id == '' ) {
				$unit_id = $this->id;
			}

			$course_id = get_post_meta( $unit_id, 'course_id', true );
			return $course_id;
		}

		function get_number_of_unit_pages( $unit_id = '' ) {
			if ( $unit_id == '' ) {
				$unit_id = $this->id;
			}

			$module	 = new Unit_Module();
			$modules = $module->get_modules( $unit_id );

			$pages_num = 1;

			foreach ( $modules as $mod ) {
				$class_name = $mod->module_type;

				if ( class_exists( $class_name ) ) {
					$module = new $class_name();

					if ( $module->name == 'page_break_module' ) {
						$pages_num++;
					}
				}
			}

			return $pages_num;
		}

		function get_unit_modules( $unit_id = '' ) {

			if ( $unit_id == '' ) {
				$unit_id = $this->id;
			}

			$args = array(
				'post_type'		 => 'module',
				'post_status'	 => 'any',
				'posts_per_page' => -1,
				'post_parent'	 => $unit_id,
				'meta_key'		 => 'module_order',
				'orderby'		 => 'meta_value_num',
				'order'			 => 'ASC',
			);

			$modules = get_posts( $args );

			return $modules;
		}

		function duplicate( $unit_id = '', $course_id = '' ) {
			global $wpdb;

			if ( $unit_id == '' ) {
				$unit_id = $this->id;
			}

			/* Duplicate course and change some data */

			$new_unit	 = $this->get_unit();
			$old_unit_id = $new_unit->ID;

			unset( $new_unit->ID );
			unset( $new_unit->guid );

			$new_unit->post_author	 = get_current_user_id();
			$new_unit->post_status	 = 'private';
			$new_unit->post_parent	 = $course_id;

			$new_unit_id = wp_insert_post( $new_unit );


			/*
			 * Duplicate course post meta
			 */

			$post_metas = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%d", $old_unit_id ) );

			if ( count( $post_metas ) != 0 ) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";

				foreach ( $post_metas as $meta_info ) {
					$meta_key		 = $meta_info->meta_key;
					$meta_value		 = addslashes( $meta_info->meta_value );
					$sql_query_sel[] = "SELECT $new_unit_id, '$meta_key', '$meta_value'";
				}

				$sql_query.= implode( " UNION ALL ", $sql_query_sel );
				$wpdb->query( $sql_query );
			}

			update_post_meta( $new_unit_id, 'course_id', $course_id );

			$unit_modules = $this->get_unit_modules( $old_unit_id );

			foreach ( $unit_modules as $unit_module ) {
				$module = new Unit_Module( $unit_module->ID );
				$module->duplicate( $unit_module->ID, $new_unit_id );
			}
		}

	}

}
?>