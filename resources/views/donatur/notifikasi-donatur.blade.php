@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div id="notifikasiApp" v-cloak class="content-wrapper py-5">

    <div class="container">
        <h2 class="text-center mb-4">NOTIFIKASI ANDA</h2>

        {{-- Loading --}}
        <div v-if="isLoading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        {{-- Belum Login --}}
        <div v-else-if="!isLoggedIn" class="text-center text-muted py-5">
            <i class="fas fa-lock fa-3x mb-3"></i>
            <p>Silakan login untuk melihat notifikasi</p>
        </div>

        {{-- Sudah Login --}}
        <div v-else>
            {{-- Header dengan tombol mark all --}}
            <div class="d-flex justify-content-between align-items-center mb-3" v-if="notifications.length > 0">
                <span class="text-muted">
                    <i class="fas fa-bell me-1"></i> 
                    @{{ unreadCount }} notifikasi belum dibaca
                </span>
                <button v-if="unreadCount > 0" @click="markAllAsRead" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                </button>
            </div>

            {{-- List Notifikasi --}}
            <div class="card shadow-sm">
                <div class="list-group list-group-flush">
                    
                    {{-- Empty State --}}
                    <div v-if="notifications.length === 0" class="list-group-item text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.3;"></i>
                        <p class="mb-0">Belum ada notifikasi</p>
                    </div>

                    {{-- Notifikasi Items --}}
                    <div v-for="notif in filteredNotifications" :key="notif.id" 
                         class="list-group-item list-group-item-action"
                         :class="{ 'bg-light': notif.status === 'unread' }"
                         @click="showNotifDetail(notif)"
                         style="cursor: pointer;">
                        
                        <div class="d-flex align-items-start">
                            {{-- Icon --}}
                            <div class="me-3">
                                <i :class="['fas', getNotifIcon(notif.type), 'fa-lg']"></i>
                            </div>
                            
                            {{-- Content --}}
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <strong :class="{ 'text-primary': notif.status === 'unread' }">
                                        @{{ notif.title }}
                                    </strong>
                                    <small class="text-muted">@{{ formatDate(notif.created_at) }}</small>
                                </div>
                                <p class="mb-0 small text-muted">@{{ notif.text }}</p>
                            </div>

                            {{-- Unread indicator --}}
                            <div v-if="notif.status === 'unread'" class="ms-2">
                                <span class="badge bg-primary rounded-pill">&nbsp;</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/notifikasi-donatur.js') }}?v={{ time() }}"></script>
@endpush
